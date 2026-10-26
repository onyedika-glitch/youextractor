<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Landing page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// About (brand / entity page for search)
Route::get('/about', function () {
    return view('about');
})->name('about');

// Unique SERP tool pages + stack guides
Route::get('/tools', [\App\Http\Controllers\SeoPageController::class, 'hub'])->name('tools.index');
Route::get('/tools/{slug}', [\App\Http\Controllers\SeoPageController::class, 'tool'])->name('tools.show');
Route::get('/for/{slug}', [\App\Http\Controllers\SeoPageController::class, 'stack'])->name('stacks.show');

// Pricing Page, Webhooks and Payment Callback
Route::get('/pricing', [PaymentController::class, 'index'])->name('pricing');
Route::post('/webhooks/bachs', [PaymentController::class, 'webhook'])->name('webhooks.bachs');
Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');

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

// SEO: dynamic sitemap (robots.txt, llms.txt, ai.txt live in routes/ai.php)
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

    // Payment & Subscription
    Route::post('/checkout/bachs', [PaymentController::class, 'checkout'])->name('checkout.bachs');

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
