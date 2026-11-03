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

<nav aria-label="Account" class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
    <a href="{{ route('tasks.index') }}" class="font-semibold">Task List</a>
    <div class="flex items-center gap-4">
        @auth
            <span>{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-blue-600 hover:underline">Log out</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Log in</a>
            <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Sign up</a>
        @endauth
    </div>
</nav>

@include("layouts._flash-message")


<main class="mx-auto max-w-6xl px-4 py-10 sm:px-6 sm:py-16 lg:px-8">
    @yield('content')
</main>
</body>
</html>
