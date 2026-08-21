<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * @param  array<int, string>  $roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $role = (string) ($user->role ?? '');

        if ($role === '' || ($roles !== [] && ! in_array($role, $roles, true))) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        return $next($request);
    }
}

