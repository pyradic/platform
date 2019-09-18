<?php

namespace Pyradic\Platform\Components;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Pyradic\Platform\Components\Service\ConfigStore;
use Pyradic\Platform\Components\Service\ComponentStore;
use Pyradic\Platform\Components\Service\ComponentRenderer;

class ComponentsServiceProvider extends ServiceProvider
{

    public function register()
    {

        $this->app->singleton('crvs.components.store',ComponentStore::class);
        $this->app->singleton('crvs.components.config',ConfigStore::class);
        $this->app->singleton('crvs.components.renderer', function (Application $app) {
            $app->make('twig');
            $renderer = new ComponentRenderer(
                $app[ 'crvs.components.store' ],
                $app[ 'twig' ],
                $app[ 'crvs.components.config' ]
            );
            return $renderer;
        });

        $this->app->extend('crvs.components.store', function(ComponentStore $store){
//            $store->registerMixin( new TestComponent());
            $store->add( new TestComponent());
            return $store;
        });



//        $this->app->alias('crvs.components.store', ComponentStore::class);

    }
}
