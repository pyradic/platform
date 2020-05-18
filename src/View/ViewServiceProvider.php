<?php

namespace Pyro\Platform\View;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Pyro\Platform\Support\Facade\Authorizer;

class ViewServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->bootBladeDirectives();
    }

    public function register()
    {
        $this->registerCustomTwigLoader();
        $this->registerPlatformNamespace();
    }

    protected function bootBladeDirectives()
    {
        $blade = resolve('blade.compiler');
        $blade->if('authorize', function ($permission, $user = null) {
            return Authorizer::authorize($permission, $user);
        });
        $blade->if('authorizeAny', function ($permissions, $user = null) {
            return Authorizer::authorizeAny($permissions, $user);
        });
        $blade->if('authorizeAll', function ($permissions, $user = null) {
            return Authorizer::authorizeAll($permissions, $user);
        });
        $blade->if('authorizeRole', function ($role, $user = null) {
            return Authorizer::authorizeRole($role, $user);
        });
        $blade->if('authorizeAnyRole', function ($roles, $user = null) {
            return Authorizer::authorizeAnyRole($roles, $user);
        });
    }

    public function registerCustomTwigLoader()
    {
        /*
         * A slight alteration in the call order to make `theme::something` work in view overrides. As the original loader 'normalizes' it a tad to soon.
         */
        $this->app->bind('twig.loader.viewfinder', function (Application $app) {
            return $app->make(Loader::class, [
                    'files'     => $app[ 'files' ],
                    'finder'    => $app[ 'view' ]->getFinder(),
                    'extension' => $app[ 'twig.extension' ],
                ]
            );
        });
    }
    public function registerPlatformNamespace()
    {
        $this->app->extend('view.finder', function (\Illuminate\View\FileViewFinder $viewFinder) {
            $viewFinder->addNamespace('platform', dirname(__DIR__) . '/../resources/views');
            return $viewFinder;
        });
    }

}
