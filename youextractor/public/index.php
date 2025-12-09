<?php

/*
|--------------------------------------------------------------------------
| Public Entry Point
|--------------------------------------------------------------------------
| This is the HTTP kernel entry point for all requests to your application.
| It loads the application bootstrap and handles the incoming request.
|
*/

use Illuminate\Contracts\Http\Kernel;
use Symfony\Component\Debug\Debug;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
*/

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
