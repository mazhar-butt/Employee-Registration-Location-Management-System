<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LocaleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Get the language from the Accept-Language header
        $locale = $request->header('Accept-Language', 'en'); // Default to English if not provided

        // Ensure the locale is supported
        if (in_array($locale, ['en', 'ur'])) {
            app()->setLocale($locale); // Set the application locale
        }

        return $next($request);
    }
}