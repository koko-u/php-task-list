<?php

namespace App\Features\Tasks\Actions;

use App\Features\Tasks\Models\Task;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class Index extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        $tasks = Task::query()->orderByDesc('created_at')->get();

        return view('tasks.index', ['tasks' => $tasks]);
    }
}
