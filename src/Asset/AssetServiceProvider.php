<?php

namespace Pyro\Platform\Asset;

use Anomaly\Streams\Platform\Event\Booting;
use Illuminate\Support\ServiceProvider;

class AssetServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerAssetOverride();
    }
    protected function registerAssetOverride()
    {
        $this->app->events->listen(Booting::class, function (Booting $event) {
            $this->app->singleton('Anomaly\Streams\Platform\Asset\Asset', Asset::class);
        });
    }

}
