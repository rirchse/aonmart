<?php

namespace App\Http\Middleware;

use App\Library\Utilities;
use Closure;
use Illuminate\Http\Request;

class StoreRestrictionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $store = Utilities::getActiveStore();
        if (empty($store)) {
            return redirect()->route('dashboard')->with('error', __('Please, Select a store first.'));
        }

        $request->attributes->add([
            'active_store_id' => $store->id
        ]);
        return $next($request);
    }
}
