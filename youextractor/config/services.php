<?php

return [
    'youtube' => [
        'key' => env('YOUTUBE_API_KEY'),
    ],
    'openai' => [
        'key' => env('OPENAI_API_KEY'),
    ],
    'gemini' => [
        'key' => env('GEMINI_API_KEY'),
    ],
    'anthropic' => [
        'key' => env('ANTHROPIC_API_KEY'),
    ],
    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('GOOGLE_REDIRECT_URI', '/auth/google/callback'),
    ],
    'github' => [
        'client_id'     => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect'      => env('GITHUB_REDIRECT_URI', '/auth/github/callback'),
    ],
];

