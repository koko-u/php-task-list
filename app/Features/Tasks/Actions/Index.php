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
        $tasks = Task::query()
            ->leftJoin('users as assignees', 'tasks.assignee_id', '=', 'assignees.id')
            ->select('tasks.*', 'assignees.name as assignee_name')
            ->orderByDesc('tasks.created_at')
            ->get();

        return view('tasks.index', ['tasks' => $tasks]);
    }
}
