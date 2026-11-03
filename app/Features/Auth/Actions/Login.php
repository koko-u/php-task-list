<?php

namespace App\Features\Auth\Actions;

use App\Features\Auth\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class Login extends Controller
{
    public function __invoke(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        $throttleKey = 'login:'.Str::lower($credentials['email']).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            throw ValidationException::withMessages([
                'email' => __('auth.throttle', ['seconds' => RateLimiter::availableIn($throttleKey)]),
            ]);
        }

        if (! Auth::attempt($credentials)) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages(['email' => __('auth.failed')]);
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        return redirect()->intended(route('tasks.index'));
    }
}
