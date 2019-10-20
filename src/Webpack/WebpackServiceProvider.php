<?php

namespace Pyro\Platform\Webpack;

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
    }

    public function boot()
    {

    }
}
