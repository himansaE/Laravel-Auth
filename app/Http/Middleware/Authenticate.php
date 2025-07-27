<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }
        $appUrl = config('app.url', '/');
        $redirectUrl = rtrim($appUrl, '/') . '/';
        // Log the redirect URL to stdout
        error_log('Redirecting unauthenticated user to: ' . $redirectUrl);
        return $redirectUrl;
    }
}
