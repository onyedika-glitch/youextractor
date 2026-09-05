#!/usr/bin/env php
<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

// Test the video extraction
try {
    $url = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';
    
    // Create a fake HTTP request
    $request = \Illuminate\Http\Request::create(
        '/api/videos/extract',
        'POST',
        [],
        [],
        [],
        ['CONTENT_TYPE' => 'application/json'],
        json_encode(['youtube_url' => $url])
    );
    
    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle($request);
    
    echo "Response Status: " . $response->status() . "\n";
    echo "Response Content:\n";
    echo $response->getContent() . "\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
