<?php

namespace Pyro\Platform\Webpack;

use Anomaly\Streams\Platform\Addon\AddonCollection;
use Anomaly\Streams\Platform\Addon\Event\AddonsHaveRegistered;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\ServiceProvider;
use Pyro\Platform\Webpack\Command\LoadWebpackData;

class WebpackServiceProvider extends ServiceProvider
{
    use DispatchesJobs;

    public function register()
    {
        $this->registerWebpack();
        $this->loadWebpackStreamAddons();

        if ($this->app[ 'config' ][ 'platform.webpack.enabled' ]) {
            $this->loadWebpackHotMiddleware();
        }
    }

    protected function loadWebpackHotMiddleware()
    {
        /** @var \Illuminate\Foundation\Http\Kernel $kernel */
        $kernel = $this->app->make(Kernel::class);
        $kernel->prependMiddleware(WebpackHotMiddleware::class);
        $this->app[ 'config' ][ 'platform.webpack.active' ] = true;
    }

    protected function registerWebpack()
    {
        $this->app->singleton('webpack', function ($app) {
            $webpack = new Webpack();
            $data    = $this->dispatchNow(new LoadWebpackData($webpack));
            $webpack->merge($data);
            return $webpack;
        });
        $this->app->alias('webpack', Webpack::class);
    }

    protected function loadWebpackStreamAddons()
    {

        $this->app->events->listen(AddonsHaveRegistered::class, function (AddonsHaveRegistered $event) {
            /** @var AddonCollection|\Anomaly\Streams\Platform\Addon\Addon[] $addons */
            $addons  = $event->getAddons()->enabled();
            $modules = $this->app->webpack->getAddons();
            foreach ($addons as $addon) {
                $composerName = $addon->getComposerJson()[ 'name' ];
                if ($module = $modules->findByComposerName($composerName)) {
                    $module->setStreamAddon($addon);
                }
            }

        });
    }
}
