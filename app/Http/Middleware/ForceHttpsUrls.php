<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class ForceHttpsUrls
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Use APP_URL from environment configuration
        $appUrl = config('app.url');
        
        // Force HTTPS scheme and use the configured APP_URL
        URL::forceScheme('https');
        URL::forceRootUrl($appUrl);

        return $next($request);
    }
}