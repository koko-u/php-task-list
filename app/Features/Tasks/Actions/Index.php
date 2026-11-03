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
        $query = Task::query();
        $dueDateColumn = $query->getQuery()->getGrammar()->wrap('tasks.dueDate');

        $tasks = $query
            ->leftJoin('users as assignees', 'tasks.assignee_id', '=', 'assignees.id')
            ->select('tasks.*', 'assignees.name as assignee_name')
            ->orderByRaw("case when {$dueDateColumn} is null then 1 else 0 end")
            ->orderBy('tasks.dueDate')
            ->orderByDesc('tasks.created_at')
            ->get();

        return view('tasks.index', ['tasks' => $tasks]);
    }
}
