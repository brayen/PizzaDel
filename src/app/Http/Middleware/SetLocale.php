<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Get locale from session (set by frontend), then from default
        $locale = Session::get('locale', config('languages.default', 'en'));
        
        // Debug log
        Log::info('SetLocale - locale from session: ' . $locale);
        Log::info('SetLocale - supported locales: ' . json_encode(config('languages.supported')));

        // Validate locale is supported
        if (in_array($locale, config('languages.supported', ['en']))) {
            App::setLocale($locale);
            Log::info('SetLocale - set locale to: ' . $locale);
        } else {
            Log::info('SetLocale - locale not supported, using default');
        }

        return $next($request);
    }
}
