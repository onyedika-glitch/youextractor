<?php

use Illuminate\Support\Facades\Route;
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

// Video extraction
Route::post('/videos/extract',  [VideoController::class, 'extract']);

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
