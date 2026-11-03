@extends('layouts.app')

@section('title', 'Index Page | Task List')

@php
    use Illuminate\Database\Eloquent\Collection;
    use App\Features\Tasks\Models\Task;
    use App\Features\Tasks\Models\TaskStatus;
    /** @var Collection<int, Task> $tasks */
@endphp

@section('content')
    <h1 class="py-4 text-3xl font-semibold text-gray-800">
        Task List
    </h1>
    <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white">
        @if ($tasks->isEmpty())
            <div class="px-6 py-4 text-slate-500">
                There are no tasks.
            </div>
        @else
            <table class="w-full text-left text-sm">
                <thead class="border-b border-slate-200 bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-6 py-3 font-medium">Title</th>
                    <th class="px-6 py-3 font-medium">Status</th>
                    <th class="px-6 py-3 font-medium">Assignee</th>
                    <th class="w-32 px-6 py-3 font-medium">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                @foreach($tasks as $task)
                    <tr>
                        <td class="px-6 py-4">{{ $task->title }}</td>
                        {{--                        <td class="px-6 py-4">{{ $task->status }}</td>--}}
                        <td class="px-6 py-4">
                            <x-progress-bar :status="$task->status"/>
                        </td>
                        <td class="px-6 py-4">{{ $task->assignee_name ?? 'Unassigned' }}</td>
                        <td class="px-6 py-4">
                            <a class="text-blue-600 underline-offset-4 hover:underline focus-visible:underline"
                               href="{{ route('tasks.show', ["task_id" => $task->id]) }}">
                                Details
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
