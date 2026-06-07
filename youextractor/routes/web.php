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

// Privacy Policy
Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

// Terms of Service
Route::get('/terms', function () {
    return view('terms');
})->name('terms');

// Blog (Markdown-powered)
Route::get('/blog', [\App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [\App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');

// SEO: robots.txt and dynamic sitemap
Route::get('/robots.txt', function () {
    return response(file_get_contents(public_path('robots.txt')), 200, ['Content-Type' => 'text/plain']);
});

Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Guest Routes (only for non-authenticated users)
|--------------------------------------------------------------------------
*/

Route::get('/debug-oauth', function () {
    try {
        $targetUrl = Laravel\Socialite\Facades\Socialite::driver('google')->redirect()->getTargetUrl();
        $parsed = parse_url($targetUrl);
        parse_str($parsed['query'] ?? '', $query);
        return [
            'app_env' => config('app.env'),
            'app_url' => config('app.url'),
            'google_redirect_config' => config('services.google.redirect'),
            'resolved_redirect_uri' => $query['redirect_uri'] ?? null,
            'target_url' => $targetUrl,
        ];
    } catch (\Exception $e) {
        return [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ];
    }
});

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

    // GitHub OAuth
    Route::get('/auth/github', [AuthController::class, 'redirectToGitHub'])->name('auth.github');
    Route::get('/auth/github/callback', [AuthController::class, 'handleGitHubCallback'])->name('auth.github.callback');
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