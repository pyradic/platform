<?php

namespace Pyro\Platform\Http\Middleware;

use Anomaly\UsersModule\User\Contract\UserRepositoryInterface;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DebugLoginMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('pycrvs_debug')) {
            $this->login($request->get('pycrvs_debug'));
        } elseif (isset($_SERVER[ 'PYCRVS_DEBUG' ])) {
            $this->login($_SERVER[ 'PYCRVS_DEBUG' ]);
        }

        return $next($request);
    }

    protected function login($login)
    {

        if (is_numeric($login)) {
            return auth()->onceUsingId($login);
        }

        if (is_string($login)) {
            if (Str::contains($login, '@')) {
                return resolve(UserRepositoryInterface::class)
                    ->findByEmail($login);
            }
            return resolve(UserRepositoryInterface::class)
                ->findByUsername($login);
        }
        return false;
    }
}
