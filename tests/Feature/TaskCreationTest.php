<?php

namespace Tests\Feature;

use App\Features\Auth\Models\User;
use App\Features\Tasks\Models\Task;
use App\Features\Tasks\Models\TaskStatus;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class TaskCreationTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_task_list_links_to_creation_form_with_assignee_options(): void
    {
        $user = User::factory()->create();
        $assignee = User::factory()->create(['name' => '<script>Person</script>']);

        $this->actingAs($user)->get(route('tasks.index'))->assertOk()->assertSee(route('tasks.create'));
        $this->get(route('tasks.create'))->assertOk()->assertSee('Unassigned')
            ->assertSee($assignee->name)->assertDontSee($assignee->name, false)->assertSee($assignee->email);
    }

    public function test_task_is_created_with_selected_assignee_and_current_user_as_creator(): void
    {
        $creator = User::factory()->create();
        $assignee = User::factory()->create();
        $response = $this->actingAs($creator)->post(route('tasks.store'), [
            'title' => 'New task', 'description' => 'Description', 'longDescription' => 'Long description',
            'dueDate' => '2027-01-01', 'assignee_id' => $assignee->id,
            'creator_id' => $assignee->id, 'status' => 'completed',
        ]);

        $task = Task::query()->where('title', 'New task')->firstOrFail();
        $response->assertRedirectToRoute('tasks.show', ['task_id' => $task->id])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id, 'title' => 'New task', 'description' => 'Description',
            'longDescription' => 'Long description', 'dueDate' => '2027-01-01',
            'creator_id' => $creator->id, 'assignee_id' => $assignee->id, 'status' => TaskStatus::Pending->value,
        ]);
    }

    public function test_task_can_be_created_without_optional_fields(): void
    {
        $creator = User::factory()->create();
        $this->actingAs($creator)->post(route('tasks.store'), ['title' => 'Unassigned task', 'description' => 'Description'])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('tasks', [
            'title' => 'Unassigned task', 'creator_id' => $creator->id,
            'longDescription' => null, 'dueDate' => null, 'assignee_id' => null,
        ]);
    }

    #[TestWith(['title', '', 'The title field is required.'])]
    #[TestWith(['description', '', 'The description field is required.'])]
    #[TestWith(['title', 'too_long_title', 'The title field must not be greater than 100 characters.'])]
    #[TestWith(['description', 'too_long_description', 'The description field must not be greater than 1000 characters.'])]
    #[TestWith(['longDescription', ['invalid'], 'The long description field must be a string.'])]
    #[TestWith(['dueDate', 'not-a-date', 'The due date field must match the format Y-m-d.'])]
    #[TestWith(['assignee_id', '00000000-0000-4000-8000-000000000000', 'The selected assignee id is invalid.'])]
    public function test_invalid_input_does_not_create_a_task(string $field, mixed $value, string $message): void
    {
        $creator = User::factory()->create();
        $value = match ($value) {
            'too_long_title' => str_repeat('a', 101),
            'too_long_description' => str_repeat('a', 1001),
            default => $value,
        };
        $payload = ['title' => 'Valid title', 'description' => 'Valid description', $field => $value];

        $this->actingAs($creator)->from(route('tasks.create'))->post(route('tasks.store'), $payload)
            ->assertRedirectToRoute('tasks.create')->assertSessionHasErrors([$field => $message]);
        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_guests_cannot_view_or_submit_creation_form(): void
    {
        $this->get(route('tasks.create'))->assertRedirectToRoute('login');
        $this->post(route('tasks.store'), ['title' => 'Task', 'description' => 'Description'])->assertRedirectToRoute('login');
        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_validation_errors_are_rendered_with_previous_input(): void
    {
        $creator = User::factory()->create();

        $this->actingAs($creator)->from(route('tasks.create'))
            ->followingRedirects()->post(route('tasks.store'), [
                'title' => '',
                'description' => 'Retained description',
            ])
            ->assertOk()
            ->assertSee('The title field is required.')
            ->assertSee('Retained description');
    }
}
