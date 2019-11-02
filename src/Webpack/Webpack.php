<?php

namespace Pyro\Platform\Webpack;

use Anomaly\Streams\Platform\Support\Hydrator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Laradic\Support\Dot;

class Webpack extends Dot
{
    public function getNamespace()
    {
        return $this[ 'output.library.0' ];
    }

    public function getPublicPath()
    {
        return $this[ 'output.publicPath' ];
    }

    /**
     * @return \Pyro\Platform\Webpack\WebpackAddonCollection|\Pyro\Platform\Webpack\WebpackAddon[]
     */
    public function getAddons()
    {
        return $this[ 'addons' ];
    }

    public function setAddons($addons)
    {
        $addons = Collection::unwrap($addons);
        $addons = WebpackAddonCollection::wrap($addons);
        $this->set('addons', $addons);
        return $this;
    }

    public function isServer()
    {
        return $this['server'] === true;
    }

    public function getMode()
    {
        return $this['mode'];
    }

    public function isMode($mode)
    {
        return $this['mode'] === $mode;
    }

    public function isDevelopment()
    {
        return $this->isMode('development');
    }

    public function isProduction()
    {
        return $this->isMode('production');
    }

}
