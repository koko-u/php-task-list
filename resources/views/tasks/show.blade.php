@php
    use App\Features\Tasks\Models\TaskStatus;
    use App\Features\Tasks\Models\Task;
    /**
     * @var Task $task
     */
@endphp
@extends('layouts.app')

@section('title', 'Task details | Task List')

@section('content')
    <section class="space-y-8">
        <div>
            <a class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
               href="{{ route('tasks.index') }}">
                <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7-7 7 7 7"/>
                </svg>
                Back to Index Page
            </a>
        </div>
        <article class="space-y-8 rounded-lg border border-slate-200 bg-white p-6">
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-3xl font-semibold">{{ $task->title }}</h1>
                <span @class([
                    'inline-flex rounded-full px-3 py-1 text-sm font-medium',
                    'bg-green-100 text-green-800' => ($task->status === TaskStatus::Completed),
                    'bg-amber-100 text-amber-800' => ($task->status === TaskStatus::Pending),
                    'bg-indigo-100 text-indigo-800' => ($task->status === TaskStatus::InProgress),
                ])>
                    {{ $task->status->toLabel() }}
                </span>
            </div>
            <dl class="flex flex-wrap gap-x-10 gap-y-4 text-sm">
                <div>
                    <dt class="font-medium text-slate-500">Assignee</dt>
                    <dd class="mt-1 text-slate-900">{{ $task->assignee?->name ?? 'Unassigned' }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-slate-500">Creator</dt>
                    <dd class="mt-1 text-slate-900">{{ $task->creator->name }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-slate-500">Due Date</dt>
                    <dd class="mt-1 text-slate-900">{{ $task->dueDate ?? 'Not set' }}</dd>
                </div>
            </dl>
            <p class="whitespace-pre-line text-slate-700">{{ $task->description }}</p>
            @if ($task->longDescription)
                <p class="whitespace-pre-line text-slate-600">{{ $task->longDescription }}</p>
            @endif
        </article>
    </section>
@endsection
