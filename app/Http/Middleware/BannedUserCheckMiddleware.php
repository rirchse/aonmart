<?php

namespace App\Http\Middleware;

use App\Library\Utilities;
use Auth;
use Closure;
use Illuminate\Http\Request;

class BannedUserCheckMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()->is_banned) {
            $ban_reason = Auth::user()->ban_reason;
            Auth::user()
                ->tokens()
                ->delete();
            return apiResponse(
                403, Utilities::getUserBannedMessage($ban_reason)
            );
        }
        return $next($request);
    }
}
