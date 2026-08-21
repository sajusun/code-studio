<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HeadlessMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isHeadless = filter_var(env('HEADLESS_MODE', false), FILTER_VALIDATE_BOOLEAN);

        // If backend is running in strict headless mode and trying to access web/view routes
        if ($isHeadless && !$request->expectsJson() && !$request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Backend is running in Headless API mode. Web page rendering is disabled.',
                'code' => 404,
                'api_documentation' => url('/api/v1'),
            ], 404);
        }

        return $next($request);
    }
}
