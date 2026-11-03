<?php

namespace App\Features\Tasks\Actions;

use App\Features\Tasks\Models\Task;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class Show extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View|RedirectResponse
    {
        $task_id = $request->route('task_id');
        $task = Task::query()->with(['assignee', 'creator'])->where('id', $task_id)->first();
        if ($task === null) {
            return redirect()->route('tasks.index')->with('error', 'Task not found');
        }

        return view('tasks.show', ['task' => $task]);
    }
}
