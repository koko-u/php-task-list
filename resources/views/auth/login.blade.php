@extends('layouts.app')

@section('title', 'Log in | Task List')

@section('content')
    <div class="mx-auto max-w-md rounded-lg border border-slate-200 bg-white p-6">
        <h1 class="mb-6 text-2xl font-semibold">Log in</h1>
        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-4">
            @csrf
            <div>
                <label for="email" class="mb-1 block text-sm font-medium">Email</label>
                <input id="email" name="email" type="email" autocomplete="email" value="{{ old('email') }}" required
                       class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-blue-500 focus:outline-blue-500"
                       @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                @error('email')
                    <p id="email-error" class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password" class="mb-1 block text-sm font-medium">Password</label>
                <input id="password" name="password" type="password" autocomplete="current-password" required
                       class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-blue-500 focus:outline-blue-500"
                       @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                @error('password')
                    <p id="password-error" class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700">Log in</button>
        </form>
        <a href="{{ route('register') }}" class="mt-4 inline-block text-sm text-blue-600 hover:underline">Create an account</a>
    </div>
@endsection
