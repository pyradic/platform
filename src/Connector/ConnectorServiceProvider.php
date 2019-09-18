<?php

namespace Pyradic\Platform\Connector;

use Illuminate\Support\ServiceProvider;

class ConnectorServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('crvs.connectors', ConnectorFactory::class);
//        $this->app->alias('crvs.connectors', ConnectorFactory::class);
    }



}
