<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Landing page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Privacy Policy
Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

// Terms of Service
Route::get('/terms', function () {
    return view('terms');
})->name('terms');

// Support / Help Page
Route::get('/support', function () {
    return view('support');
})->name('support');

// Blog (Markdown-powered)
Route::get('/blog', [\App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [\App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');

// SEO: robots.txt and dynamic sitemap
Route::get('/robots.txt', function () {
    return response(file_get_contents(public_path('robots.txt')), 200, ['Content-Type' => 'text/plain']);
});

Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index']);

// API Documentation
Route::get('/api-docs', function () {
    return response(file_get_contents(public_path('api-docs.html')), 200, ['Content-Type' => 'text/html']);
})->name('api-docs');

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

    Route::get('/videos/{video}', function (\App\Models\Video $video) {
        return view('videos.show', ['video' => $video]);
    })->name('videos.show');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Email template previews (local/dev only)
|--------------------------------------------------------------------------
| Visit /dev/emails/welcome, /dev/emails/daily-reminder,
| /dev/emails/activity/login, /dev/emails/activity/logout to see the
| rendered HTML in your browser. Disabled in production.
*/
if (! app()->environment('production')) {
    Route::get('/dev/emails/welcome', function () {
        $user = \App\Models\User::first() ?? new \App\Models\User(['name' => 'Ada Lovelace', 'email' => 'ada@example.com']);
        return new \App\Mail\WelcomeEmail($user);
    });

    Route::get('/dev/emails/daily-reminder', function () {
        $user = \App\Models\User::first() ?? new \App\Models\User(['name' => 'Ada Lovelace', 'email' => 'ada@example.com']);
        return new \App\Mail\DailyReminderEmail($user);
    });

    Route::get('/dev/emails/activity/{type}', function (string $type) {
        $user = \App\Models\User::first() ?? new \App\Models\User(['name' => 'Ada Lovelace', 'email' => 'ada@example.com']);
        return new \App\Mail\ActivityNotification($user, $type, [
            'ip' => '203.0.113.42',
            'device' => 'Chrome on macOS',
            'location' => 'Lagos, Nigeria',
        ]);
    });
}
