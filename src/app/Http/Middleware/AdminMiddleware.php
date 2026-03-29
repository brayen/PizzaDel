<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('staff')->user();
        
        if (!$user || !in_array($user->position, ['admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
