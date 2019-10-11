<?php

namespace Pyro\Platform\Bus;

use Illuminate\Contracts\Queue\Factory as QueueFactoryContract;
use Illuminate\Support\ServiceProvider;

class BusServiceProvider extends ServiceProvider
{
    public $providers = [];

    public $bindings = [];

    public $singletons = [];

    public function boot()
    {

    }

    public function register()
    {
        $this->app->registerDeferredProvider(\Illuminate\Bus\BusServiceProvider::class);

        $this->app->singleton(Dispatcher::class, function ($app) {
            return new Dispatcher($app, function ($connection = null) use ($app) {
                return $app[QueueFactoryContract::class]->connection($connection);
            });
        });
        $this->app->alias(\Pyro\Platform\Bus\Dispatcher::class, \Illuminate\Bus\Dispatcher::class);
    }

    public function provides()
    {
        return [
            \Illuminate\Bus\Dispatcher::class,
            \Illuminate\Contracts\Bus\Dispatcher::class,
        ];
    }
}
