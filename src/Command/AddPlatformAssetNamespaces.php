<?php

namespace Pyro\Platform\Command;

use Anomaly\Streams\Platform\Application\Application;
use Anomaly\Streams\Platform\Asset\Asset;
use Illuminate\Contracts\Container\Container;

class AddPlatformAssetNamespaces
{
    public function handle(Asset $asset, Container $container, Application $application)
    {
        $asset->addPath('node_modules', base_path('node_modules'));
        $asset->addPath('platform', dirname(__DIR__) . '/../resources');

    }
}
