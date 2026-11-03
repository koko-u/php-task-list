<?php

use App\Http\Controllers\Task;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('tasks.index'));
Route::get('/tasks', \App\Features\Tasks\Actions\Index::class)->name('tasks.index');
Route::get('/tasks/{task_id}', \App\Features\Tasks\Actions\Show::class)
    ->whereUuid('task_id')
    ->name('tasks.show');
