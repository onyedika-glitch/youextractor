<?php

namespace App\Http\Middleware;

use Illuminate\Routing\Middleware\ValidateSignature as Middleware;
use Illuminate\Http\Request;

class ValidateSignature extends Middleware
{
    protected $except = [
        // Exclude URLs from CSRF verification
    ];

    public function handle(Request $request, \Closure $next)
    {
        if ($request->hasValidSignature()) {
            return $next($request);
        }

        throw new \Illuminate\Routing\Exceptions\InvalidSignatureException;
    }
}
