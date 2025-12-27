<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Landing Page
Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/app');
    }
    return view('welcome');
})->name('home');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/signup', function () {
        return view('auth.register');
    })->name('register');

    Route::get('/register', function () {
        return view('auth.register');
    });

    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle']);
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// App Routes (Protected)
Route::middleware(['auth'])->group(function () {
    Route::get('/app', function () {
        return view('app.extractor');
    })->name('app.extractor');
    
    Route::get('/videos', function () {
        return view('videos.list');
    })->name('videos.list');
    
    Route::get('/videos/{video}', function ($video) {
        return view('videos.show', ['video' => $video]);
    })->name('videos.show');
});
