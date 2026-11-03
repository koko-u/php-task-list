<?php

namespace App\Features\Tasks\Actions;

use App\Features\Auth\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class Create extends Controller
{
    public function __invoke(): View
    {
        return view('tasks.create', ['users' => User::query()->orderBy('name')->get(['id', 'name', 'email'])]);
    }
}
