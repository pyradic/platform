<?php

namespace Pyro\Platform\Webpack;

use Anomaly\Streams\Platform\Addon\AddonCollection;
use Anomaly\Streams\Platform\Event\Booted;
use Anomaly\Streams\Platform\Event\Ready;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;

class WebpackServiceProvider extends ServiceProvider
{
    public function register()
    {
        if ($this->app[ 'config' ][ 'platform.webpack.enabled' ]) {
            /** @var \Illuminate\Foundation\Http\Kernel $kernel */
            $kernel = $this->app->make(Kernel::class);
            $kernel->prependMiddleware(WebpackHotMiddleware::class);
        }
        $path                                               = base_path($this->app[ 'config' ][ 'platform.webpack.path' ]);
        $this->app[ 'config' ][ 'platform.webpack.active' ] = file_exists($path);

        $this->app->events->listen(Ready::class, function(){
            $addons=$this->app->make(AddonCollection::class);
            return ;
        });
        $this->app->singleton('webpack', function($app) {
            $wpjson = new Webpack($app['files']);
            $path=base_path($this->app[ 'config' ][ 'platform.webpack.path' ]);
            $wpjson->loadFromPath($path);
            return $wpjson;
        });
        $this->app->alias('webpack',Webpack::class);

    }
}
