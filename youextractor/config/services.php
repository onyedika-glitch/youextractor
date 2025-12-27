<?php

return [
    'youtube' => [
        'key' => env('YOUTUBE_API_KEY'),
    ],
    'openai' => [
        'key' => env('OPENAI_API_KEY'),
    ],
    
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI', 'http://localhost:10000/auth/google/callback'),
    ],
];
