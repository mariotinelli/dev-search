<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $roles)
    {
        $roles = explode('|', $roles);

        foreach ($roles as $role) {
            if (auth()->user()->hasRole($role) || auth()->user()->isAdmin()) {
                return $next($request);
            }
        }

        abort(403);
    }
}
