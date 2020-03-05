<?php

namespace Pyro\Platform\Routing;

use Anomaly\Streams\Platform\Addon\AddonProvider;
use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class RoutingServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('route_crumbs', function(){
            return new RouteCrumbs();
        });
        $this->app->alias('route_crumbs',RouteCrumbs::class);

        $this->app->bind(\Illuminate\Routing\Contracts\ControllerDispatcher::class, ControllerDispatcher::class);
//
//        AddonProvider::when('register',
//            /**
//             * @param \Anomaly\Streams\Platform\Addon\AddonServiceProvider $provider
//             * @param \Anomaly\Streams\Platform\Addon\Addon                $addon
//             */
//            function ($provider, $addon) {
//                foreach ($provider->getRoutes() as $uri => $route) {
//                    if ( ! isset($route[ 'breadcrumb' ])) {
//                        continue;
//                    }
//                    $route[ 'uri' ] = $uri;
//                    $this->app->route_crumbs->addFromProvider($route, $addon);
//                }
//            });
    }


}
