<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;

class MerchantGuard
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

        return $role === 'merchant' ? $next($request) : abort(403, 'Forbidden');
    }
}
