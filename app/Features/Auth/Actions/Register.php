<?php

namespace App\Features\Auth\Actions;

use App\Features\Auth\Models\User;
use App\Features\Auth\Requests\RegisterRequest;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class Register extends Controller
{
    public function __invoke(RegisterRequest $request): RedirectResponse
    {
        $attributes = $request->validated();

        $user = User::create($attributes);
        event(new Registered($user));
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('tasks.index');
    }
}
