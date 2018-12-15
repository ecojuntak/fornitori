<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;

class AdminGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $role = JWTAuth::parseToken()->toUser()->role;

        return $role === 'admin' ? $next($request) : abort(403, 'Forbidden');
    }
}
