<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\EmailController;

Route::get('/', function () {
    $appUrl = rtrim(config('app.url', '/'), '/');
    return view('welcome', ['appUrl' => $appUrl]);
});

Route::get('/auth/redirect', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google.redirect');
Route::get('/auth/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks');
    Route::get('/emails', [EmailController::class, 'index'])->name('emails');
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/');
    })->name('logout');
});
