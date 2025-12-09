<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/videos', function () {
    return view('videos.list');
})->name('videos.list');

Route::get('/videos/{video}', function ($video) {
    return view('videos.show', ['video' => $video]);
})->name('videos.show');
