<?php

namespace App\Http\Middleware;

use Closure;

class Cors
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
        $appToken = $request->header('AppToken');

//        if($appToken !== env('APP_TOKEN')) {
//            abort(401, 'Access denied', ['App-Token' => $appToken]);
//        }

        $response = $next($request);
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Headers', 'Authorization, App-Token, Content-Type');
        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, OPTION');

        return $response;
    }
}
