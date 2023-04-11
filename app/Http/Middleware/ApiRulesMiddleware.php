<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiRulesMiddleware
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
        if ($request->header('accept') != 'application/json') {
            return apiResponse(406, "The request header must have 'Accept': 'application/json'");
        }
        return $next($request);
    }
}
