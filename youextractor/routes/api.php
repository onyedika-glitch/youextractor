<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\VideoController;

/*
|--------------------------------------------------------------------------
| API Routes — YouExtractor
|--------------------------------------------------------------------------
*/

Route::get('/health', function () {
    return response()->json([
        'status'         => 'ok',
        'gemini_key'     => !empty(env('GEMINI_API_KEY'))     ? 'configured' : 'missing',
        'openai_key'     => !empty(env('OPENAI_API_KEY'))     ? 'configured' : 'missing',
        'anthropic_key'  => !empty(env('ANTHROPIC_API_KEY'))  ? 'configured' : 'missing',
        'queue_driver'   => env('QUEUE_CONNECTION', 'sync'),
    ]);
});

/*
|--------------------------------------------------------------------------
| Public session routes — in-panel auth for the Chrome extension
|--------------------------------------------------------------------------
| Session middleware only (no "auth"), so login/register can start a
| session. No CSRF middleware here: the extension posts from an extension
| origin with the session cookie attached manually.
*/

Route::middleware([
    \Illuminate\Cookie\Middleware\EncryptCookies::class,
    \Illuminate\Session\Middleware\StartSession::class,
    \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
])->group(function () {
    Route::post('/auth/login',    [AuthController::class, 'apiLogin']);
    Route::post('/auth/register', [AuthController::class, 'apiRegister']);
});

Route::middleware([
    \Illuminate\Cookie\Middleware\EncryptCookies::class,
    \Illuminate\Session\Middleware\StartSession::class,
    \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
    'auth'
])->group(function () {
    // In-panel logout
    Route::post('/auth/logout', [AuthController::class, 'apiLogout']);

    // Video extraction
    Route::post('/videos/extract',  [VideoController::class, 'extract']);

    // Current user (used by the Chrome extension to detect the login session)
    Route::get('/me', [AuthController::class, 'me']);

    // List & search
    Route::get('/videos',           [VideoController::class, 'index']);
    Route::get('/videos/search',    [VideoController::class, 'search']);

    // Single video + status polling
    Route::get('/videos/{video}',           [VideoController::class, 'show']);
    Route::get('/videos/{video}/status',    [VideoController::class, 'status']);

    // Actions
    Route::get( '/videos/{video}/download',       [VideoController::class, 'downloadCode']);
    Route::post('/videos/{video}/re-extract',     [VideoController::class, 'reExtractCode']);
    Route::post('/videos/{video}/push-to-github', [VideoController::class, 'pushToGitHub']);
    Route::post('/videos/{video}/chat',           [VideoController::class, 'chat']);
});
