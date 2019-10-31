<?php

namespace Pyro\Platform\Webpack;

use Anomaly\Streams\Platform\Support\Hydrator;
use Illuminate\Filesystem\Filesystem;
use Laradic\Support\Dot;

class Webpack extends Dot
{
    /** @var \Illuminate\Filesystem\Filesystem */
    protected $fs;

    /**
     * WebpackJson constructor.
     *
     * @param \Illuminate\Filesystem\Filesystem $fs
     */
    public function __construct(Filesystem $fs)
    {
        parent::__construct();
        $this->fs = $fs;
    }

    public function loadFromPath($filePath)
    {
        $json             = $this->fs->get($filePath);
        $this->items      = json_decode($json, true);
        $addons           = $this
            ->collect('addons')
            ->cast(Dot::class)
            ->map(function (Dot $data) {
                $addon = new WebpackAddon($this);
                with(new Hydrator())->hydrate($addon, $data->toArray());
                $addon
                    ->setName($data[ 'pkg.name' ])
                    ->setComposerName($data[ 'composer.name' ])
                    ->setComposerType($data[ 'composer.type' ]);
                return $addon;
            })
            ->all();
        $this[ 'addons' ] = WebpackAddonCollection::make($addons);
        return $this;
    }

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
