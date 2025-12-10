<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VideoController;

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'openai_key' => !empty(env('OPENAI_API_KEY')) ? 'configured' : 'missing',
        'youtube_key' => !empty(env('YOUTUBE_API_KEY')) ? 'configured' : 'missing',
    ]);
});

Route::post('/videos/extract', [VideoController::class, 'extract']);
Route::get('/videos', [VideoController::class, 'index']);
Route::get('/videos/search', [VideoController::class, 'search']);
Route::get('/videos/{video}', [VideoController::class, 'show']);
Route::get('/videos/{video}/download', [VideoController::class, 'downloadCode']);
Route::post('/videos/{video}/re-extract', [VideoController::class, 'reExtractCode']);
