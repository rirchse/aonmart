<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SanctumOptionalAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->bearerToken()) {
            try {
                Auth::setUser(
                    Auth::guard('sanctum')->user()
                );
            } catch (\Throwable $th) {
                return apiResponse(401, 'Authentication Failed.');
            }
        }
        return $next($request);
    }
}
