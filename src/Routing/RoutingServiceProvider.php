<?php

namespace Pyro\Platform\Routing;

use Anomaly\Streams\Platform\Addon\AddonProvider;
use Anomaly\Streams\Platform\Addon\Event\AddonsHaveRegistered;
use Illuminate\Support\ServiceProvider;

class RoutingServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('route_crumbs', function () {
            return new RouteCrumbs();
        });
        $this->app->alias('route_crumbs', RouteCrumbs::class);

        $this->app->bind(\Illuminate\Routing\Contracts\ControllerDispatcher::class, ControllerDispatcher::class);

        $this->app->events->listen(AddonsHaveRegistered::class, function (AddonsHaveRegistered $event) {
            $addons = $event->getAddons();
            $this->app->route_crumbs->entry(request());
        });
//
        AddonProvider::when('register',
            /**
             * @param \Anomaly\Streams\Platform\Addon\AddonServiceProvider $provider
             * @param \Anomaly\Streams\Platform\Addon\Addon                $addon
             */
            function ($provider, $addon) {
                $routes = $provider->getRoutes();
                foreach ($routes as $uri => &$route) {
                    if (is_array($route)) {
//                        if ( ! isset($route[ 'url' ])) {
//                            $route[ 'url' ] = $uri;
//                        }
                        if ( ! isset($route[ 'uses' ])) {
                            $route[ 'uses' ] = null;
                        }
                    }
                }

                $provider->setRoutes($routes);
            });
    }

}
