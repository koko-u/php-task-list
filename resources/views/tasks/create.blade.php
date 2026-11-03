@extends('layouts.app')

@section('title', 'Create New Task | Task List')

@section('content')
    <div class="mx-auto max-w-2xl rounded-lg border border-slate-200 bg-white p-6">
        <h1 class="mb-6 text-2xl font-semibold">Create New Task</h1>
        <form method="POST" action="{{ route('tasks.store') }}" class="flex flex-col gap-4" novalidate>
            @csrf
            <div>
                <label for="title" class="mb-1 block text-sm font-medium">Title</label>
                <input id="title" name="title" type="text" value="{{ old('title') }}"
                       class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-blue-500 focus:outline-blue-500"
                       @error('title') aria-invalid="true" aria-describedby="title-error" @enderror>
                @error('title') <p id="title-error" class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="description" class="mb-1 block text-sm font-medium">Description</label>
                <textarea id="description" name="description" rows="3"
                          class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-blue-500 focus:outline-blue-500"
                          @error('description') aria-invalid="true" aria-describedby="description-error" @enderror>{{ old('description') }}</textarea>
                @error('description') <p id="description-error" class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="longDescription" class="mb-1 block text-sm font-medium">Long Description</label>
                <textarea id="longDescription" name="longDescription" rows="5"
                          class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-blue-500 focus:outline-blue-500"
                          @error('longDescription') aria-invalid="true" aria-describedby="longDescription-error" @enderror>{{ old('longDescription') }}</textarea>
                @error('longDescription') <p id="longDescription-error" class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="dueDate" class="mb-1 block text-sm font-medium">Due Date</label>
                <input id="dueDate" name="dueDate" type="date" value="{{ old('dueDate') }}"
                       class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-blue-500 focus:outline-blue-500"
                       @error('dueDate') aria-invalid="true" aria-describedby="dueDate-error" @enderror>
                @error('dueDate') <p id="dueDate-error" class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="assignee_id" class="mb-1 block text-sm font-medium">Assignee</label>
                <select id="assignee_id" name="assignee_id"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-blue-500 focus:outline-blue-500"
                        @error('assignee_id') aria-invalid="true" aria-describedby="assignee_id-error" @enderror>
                    <option value="">Unassigned</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @selected(old('assignee_id') === $user->id)>{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                @error('assignee_id') <p id="assignee_id-error" class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-center gap-4">
                <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700">Create Task</button>
                <a href="{{ route('tasks.index') }}" class="text-blue-600 hover:underline">Cancel</a>
            </div>
        </form>
    </div>
@endsection
