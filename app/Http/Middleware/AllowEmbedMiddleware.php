<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AllowEmbedMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $response->header('X-Frame-Options', 'ALLOW-FROM https://project001.lubirastudios.com');
        return $response;
    }
}