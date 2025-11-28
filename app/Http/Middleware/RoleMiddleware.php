<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $roles  Comma-separated roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized');
        }

        // Convert comma-separated roles into an array
        $rolesArray = explode(',', $roles);

        // Check if the user's role is in the allowed roles
        if (!in_array(Auth::user()->role, $rolesArray)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}

class AdminMiddleware
{
    /**
     * Handle an incoming request for admin-only access.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
