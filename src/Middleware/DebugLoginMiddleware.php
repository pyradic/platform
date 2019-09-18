<?php

namespace Pyradic\Platform\Middleware;

use Closure;
use Illuminate\Http\Request;

class DebugLoginMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('pycrvs_debug')) {
            auth()->onceUsingId($request->get('pycrvs_debug'));
        } elseif (isset($_SERVER[ 'PYCRVS_DEBUG' ])) {
            auth()->onceUsingId($_SERVER[ 'PYCRVS_DEBUG' ]);
        }
        return $next($request);
    }

}
