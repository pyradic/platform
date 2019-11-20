<?php

namespace Pyro\Platform\Webpack\Command;

use Anomaly\Streams\Platform\Support\Hydrator;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Laradic\Support\Wrap;
use Pyro\Platform\Webpack\Webpack;
use Pyro\Platform\Webpack\WebpackAddon;
use Pyro\Platform\Webpack\WebpackAddonCollection;

class LoadWebpackData
{
    /** @var \Pyro\Platform\Webpack\Webpack */
    protected $webpack;

    public function __construct(Webpack $webpack)
    {
        $this->webpack = $webpack;
    }

    public function handle(Repository $config, Filesystem $fs)
    {
        $path = $config['platform::webpack.path'];
        $path = path_is_relative($path) ? base_path($path) : $path;
        $json = $fs->get($path);
        $data = json_decode($json, true);

        $hydrator = new Hydrator();
        $addons   = new WebpackAddonCollection();
        foreach (data_get($data, 'addons', []) as $addonData) {
            $addons->push($addon = new WebpackAddon($this->webpack));
            $addon->setData($addonData);
        }
        data_set($data, 'addons', $addons);

        return $data;
    }
}
