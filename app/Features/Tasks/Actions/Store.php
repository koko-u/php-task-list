<?php

namespace App\Features\Tasks\Actions;

use App\Features\Tasks\Models\Task;
use App\Features\Tasks\Requests\StoreTaskRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class Store extends Controller
{
    public function __invoke(StoreTaskRequest $request): RedirectResponse
    {
        $attributes = $request->validated();
        $task = new Task;
        $task->title = $attributes['title'];
        $task->description = $attributes['description'];
        $task->longDescription = $attributes['longDescription'] ?? null;
        $task->dueDate = $attributes['dueDate'] ?? null;
        $task->assignee_id = $attributes['assignee_id'] ?? null;
        $task->creator_id = $request->user()->id;
        $task->save();

        return redirect()->route('tasks.show', ['task_id' => $task->id])->with('success', 'Task created successfully.');
    }
}
