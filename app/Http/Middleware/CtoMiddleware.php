<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CtoMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->user()->isCto()) {
            abort(403);
        }

        return $next($request);
    }
}
