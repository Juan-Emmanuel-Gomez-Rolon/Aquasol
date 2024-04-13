<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Get the authenticated user
        $user = $request->user();

        // Check if the user has any of the specified roles
        if ($user && in_array($user->role, $roles)) {
            return $next($request);
        }

        // show 404 to avoid giving hints about the existence of the route
        abort(404);
    }
}
