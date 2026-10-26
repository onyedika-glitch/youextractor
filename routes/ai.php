<?php

use App\Http\Controllers\AiFilesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Crawler & AI agent files
|--------------------------------------------------------------------------
| Registered without the "web" middleware group (no session or cookies) so
| CDNs can cache them. Page.md URLs and Accept: text/markdown are handled by
| the ServeMarkdown global middleware.
*/

Route::get('/robots.txt', [AiFilesController::class, 'robots']);
Route::get('/llms.txt', [AiFilesController::class, 'llms']);
Route::get('/llms-full.txt', [AiFilesController::class, 'llmsFull']);
Route::get('/ai.txt', [AiFilesController::class, 'aiTxt']);

Route::match(['GET', 'HEAD', 'OPTIONS'], '/api/md/{path?}', [AiFilesController::class, 'markdown'])
    ->where('path', '.*');
