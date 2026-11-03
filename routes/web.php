<?php

use App\Features\Auth\Actions\Login;
use App\Features\Auth\Actions\Logout;
use App\Features\Auth\Actions\Register;
use App\Features\Tasks\Actions\Index;
use App\Features\Tasks\Actions\Show;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('tasks.index'));

Route::middleware('guest')->group(function (): void {
    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', Login::class)->name('login.store');
    Route::view('/register', 'auth.register')->name('register');
    Route::post('/register', Register::class)->middleware('throttle:5,1')->name('register.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', Logout::class)->name('logout');
    Route::get('/tasks', Index::class)->name('tasks.index');
    Route::get('/tasks/{task_id}', Show::class)
        ->whereUuid('task_id')
        ->name('tasks.show');
});
