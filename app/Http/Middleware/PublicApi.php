<?php

namespace App\Http\Middleware;

use Closure;

class PublicApi
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
        $appToken = $request->header('app-token');

        if($appToken === env('APP_TOKEN')) {
            return $next($request);
        }

        abort(403, 'Access denied');
    }
}
