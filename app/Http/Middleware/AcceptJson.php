<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AcceptJson
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Laravel will automatically convert the response to JSON
        // if the request has this header.

        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
