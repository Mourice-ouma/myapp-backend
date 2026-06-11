<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $allowed = explode('|', $role);

        if (! in_array($user->role, $allowed)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
