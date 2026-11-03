<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use LazilyRefreshDatabase;

    #[TestWith(['login'])]
    #[TestWith(['register'])]
    public function test_guests_can_view_authentication_forms(string $route): void
    {
        $this->get(route($route))->assertOk()->assertViewIs('auth.'.$route);
    }

    #[TestWith(['login'])]
    #[TestWith(['register'])]
    public function test_authenticated_users_are_redirected_from_guest_forms(string $route): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route($route))->assertRedirectToRoute('tasks.index');
    }

    public function test_registration_creates_a_user_and_logs_them_in(): void
    {
        Event::fake([Registered::class]);

        $response = $this->post(route('register.store'), [
            'name' => 'Test User',
            'email' => 'new@example.com',
            'password' => 'secure-password',
            'password_confirmation' => 'secure-password',
            'email_verified_at' => now(),
        ]);

        $response->assertRedirectToRoute('tasks.index')->assertSessionHasNoErrors();
        $user = User::where('email', 'new@example.com')->firstOrFail();
        $this->assertAuthenticatedAs($user);
        $this->assertSame('Test User', $user->name);
        $this->assertNull($user->email_verified_at);
        $this->assertTrue(Hash::check('secure-password', $user->password));
        Event::assertDispatched(Registered::class, fn (Registered $event): bool => $event->user->is($user));
    }

    #[TestWith(['name', '', 'The name field is required.'])]
    #[TestWith(['name', ['invalid'], 'The name field must be a string.'])]
    #[TestWith(['email', '', 'The email field is required.'])]
    #[TestWith(['email', 'invalid', 'The email field must be a valid email address.'])]
    #[TestWith(['password', '', 'The password field is required.'])]
    #[TestWith(['password', 'short', 'The password field must be at least 8 characters.'])]
    #[TestWith(['password_confirmation', 'different', 'The password field confirmation does not match.'])]
    public function test_invalid_registration_does_not_create_a_user(string $field, mixed $value, string $message): void
    {
        $payload = [
            'name' => 'Test User',
            'email' => 'new@example.com',
            'password' => 'secure-password',
            'password_confirmation' => 'secure-password',
        ];
        $payload[$field] = $value;
        if ($field === 'password') {
            $payload['password_confirmation'] = $value;
        }

        $response = $this->from(route('register'))->post(route('register.store'), $payload);

        $errorField = $field === 'password_confirmation' ? 'password' : $field;
        $response->assertRedirectToRoute('register')->assertSessionHasErrors([$errorField => $message]);
        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
        $response->assertSessionMissing('_old_input.password');
        $response->assertSessionMissing('_old_input.password_confirmation');
    }

    #[TestWith(['password', 'The password must not exceed 72 bytes.'])]
    #[TestWith(['multibyte', 'The password must not exceed 72 bytes.'])]
    #[TestWith(['null', 'The password must not contain null characters.'])]
    public function test_passwords_unsupported_by_bcrypt_are_rejected(string $case, string $message): void
    {
        $password = match ($case) {
            'multibyte' => str_repeat('あ', 25),
            'null' => "secure\0password",
            default => str_repeat('a', 73),
        };

        $response = $this->post(route('register.store'), [
            'name' => 'Test User',
            'email' => 'new@example.com',
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $response->assertSessionHasErrors(['password' => $message]);
        $this->assertDatabaseCount('users', 0);
        $this->assertGuest();
    }

    public function test_duplicate_email_is_rejected(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('register.store'), [
            'name' => 'Another User',
            'email' => $user->email,
            'password' => 'secure-password',
            'password_confirmation' => 'secure-password',
        ]);

        $response->assertSessionHasErrors(['email' => 'The email has already been taken.']);
        $this->assertGuest();
        $this->assertDatabaseCount('users', 1);
    }

    public function test_users_can_log_in_and_the_session_is_regenerated(): void
    {
        $user = User::factory()->create();
        $this->withSession(['marker' => 'retained']);
        $sessionId = session()->getId();

        $response = $this->post(route('login.store'), ['email' => $user->email, 'password' => 'password']);

        $response->assertRedirectToRoute('tasks.index')->assertSessionHasNoErrors();
        $this->assertAuthenticatedAs($user);
        $this->assertNotSame($sessionId, session()->getId());
    }

    public function test_login_returns_users_to_the_intended_page(): void
    {
        $user = User::factory()->create();
        $this->get(route('tasks.index'))->assertRedirectToRoute('login');

        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'password'])
            ->assertRedirectToRoute('tasks.index');

        $this->assertAuthenticatedAs($user);
    }

    public function test_incorrect_password_is_rejected(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('login.store'), ['email' => $user->email, 'password' => 'wrong-password']);

        $response->assertSessionHasErrors(['email' => __('auth.failed')]);
        $this->assertGuest();
    }

    public function test_login_requires_credentials(): void
    {
        $this->post(route('login.store'), [])->assertSessionHasErrors(['email', 'password']);

        $this->assertGuest();
    }

    public function test_login_attempts_are_limited_and_can_resume_after_a_minute(): void
    {
        $this->freezeTime();
        $user = User::factory()->create();
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post(route('login.store'), ['email' => $user->email, 'password' => 'wrong-password']);
        }

        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'password'])
            ->assertSessionHasErrors(['email' => __('auth.throttle', ['seconds' => 60])]);
        $this->assertGuest();

        $this->travel(61)->seconds();
        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'password'])
            ->assertRedirectToRoute('tasks.index');
        $this->assertAuthenticatedAs($user);
    }

    public function test_logout_invalidates_the_session_and_regenerates_the_csrf_token(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->withSession(['private-data' => 'secret', '_token' => 'old-token']);
        $sessionId = session()->getId();

        $response = $this->post(route('logout'));

        $response->assertRedirectToRoute('login')->assertSessionMissing('private-data');
        $this->assertGuest();
        $this->assertNotSame($sessionId, session()->getId());
        $this->assertNotSame('old-token', session()->token());
        $this->get(route('tasks.index'))->assertRedirectToRoute('login');
    }

    #[TestWith(['/tasks'])]
    #[TestWith(['/tasks/00000000-0000-4000-8000-000000000000'])]
    public function test_task_pages_require_authentication(string $path): void
    {
        $this->get($path)->assertRedirectToRoute('login');
    }

    public function test_authenticated_users_can_view_tasks_and_names_are_escaped(): void
    {
        $user = User::factory()->create(['name' => '<script>alert(1)</script>']);

        $this->actingAs($user)->get(route('tasks.index'))
            ->assertOk()
            ->assertSee($user->name)
            ->assertDontSee($user->name, false)
            ->assertSee('Log out');
    }

    public function test_logout_requires_authentication(): void
    {
        $this->post(route('logout'))->assertRedirectToRoute('login');
    }

    public function test_logout_cannot_be_requested_with_get(): void
    {
        $this->get('/logout')->assertMethodNotAllowed();
    }
}
