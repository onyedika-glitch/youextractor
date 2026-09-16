<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    protected $except = [
        'api/webhooks/*',
        'webhooks/*',
        'api/webhooks/bachs',
        'webhooks/bachs',
    ];
}
