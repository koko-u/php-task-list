<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Task List')</title>

    @fonts
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>
<body class="min-h-screen bg-gray-100 font-sans text-slate-900 antialiased">

<header class="bg-slate-800 text-white shadow-md">
    <nav aria-label="Account" class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
        <a href="{{ route('tasks.index') }}" class="text-lg font-semibold tracking-tight">Task List</a>
        <div class="flex flex-wrap items-center gap-3">
            @auth
                <span class="text-sm text-slate-200">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex min-h-10 cursor-pointer items-center justify-center rounded-md border border-slate-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:border-slate-400 hover:bg-slate-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-400">Log out</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="inline-flex min-h-10 items-center justify-center rounded-md border border-slate-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:border-slate-400 hover:bg-slate-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-400">Log in</a>
                <a href="{{ route('register') }}" class="inline-flex min-h-10 items-center justify-center rounded-md border border-blue-500 bg-blue-500 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:border-blue-600 hover:bg-blue-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-400">Sign up</a>
            @endauth
        </div>
    </nav>
</header>

@include("layouts._flash-message")


<main class="mx-auto max-w-6xl px-4 py-10 sm:px-6 sm:py-16 lg:px-8">
    @yield('content')
</main>
</body>
</html>
