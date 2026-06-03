<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Landing page
Route::get('/', function () {
    return view('landing');
})->name('landing');

/*
|--------------------------------------------------------------------------
| Guest Routes (only for non-authenticated users)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    // Sign Up
    Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
    Route::post('/signup', [AuthController::class, 'signup'])->name('signup.submit');

    // Sign In
    Route::get('/signin', [AuthController::class, 'showSignin'])->name('signin');
    Route::post('/signin', [AuthController::class, 'signin'])->name('signin.submit');

    // Google OAuth
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

/*
|--------------------------------------------------------------------------
| Protected Routes (require authentication)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Dashboard (main extraction tool)
    Route::get('/dashboard', function () {
        return view('index');
    })->name('dashboard');

    // Videos
    Route::get('/videos', function () {
        return view('videos.list');
    })->name('videos.list');

    Route::get('/videos/{video}', function ($video) {
        return view('videos.show', ['video' => $video]);
    })->name('videos.show');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
