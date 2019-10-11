<?php

namespace Pyro\Platform\Command;

use Anomaly\Streams\Platform\Addon\Addon;
use Anomaly\Streams\Platform\Addon\AddonCollection;
use Anomaly\Streams\Platform\Support\Configurator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Pyro\Platform\DebugRequestVars;

class AddAddonOverrides
{
    /** @var \Anomaly\Streams\Platform\Addon\Addon */
    protected $addon;

    public function __construct(Addon $addon)
    {
        $this->addon = $addon;
    }

    public function handle(AddonCollection $addons, Filesystem $fs, Factory $factory, Configurator $configurator, Request $request)
    {

        if(config('crvs.accept_debug_request_vars',false) && request()->has('NO_ADDON_OVERRIDES')){return;        }
        $overridePaths = glob($this->addon->getPath('resources/addons/*/*'), GLOB_NOSORT);
        foreach ($overridePaths as $overridePath) {
            $namespace   = $this->getAddonNamespace($overridePath);
            if(!$addons->has($namespace)){
                continue;
            }
            $targetAddon = $addons->get($namespace);
            if ($fs->exists($viewPath = $overridePath . '/views')) {
                $factory->getFinder()->prependNamespace($targetAddon->getNamespace(), $viewPath);
            }
            if ($fs->exists($configPath = $overridePath . '/config')) {
                $configurator->addNamespaceOverrides($targetAddon->getNamespace(), $configPath);
            }
        }

        // streams::
        if ($fs->exists($streamsOverridePath = $this->addon->getPath('resources/streams'))) {
            if ($fs->exists($viewPath = $streamsOverridePath . '/views')) {
                $factory->getFinder()->prependNamespace('streams', $viewPath);
            }
            if ($fs->exists($configPath = $streamsOverridePath . '/config')) {
                $configurator->addNamespaceOverrides('streams', $configPath);
            }
        }
    }

    protected function getAddonNamespace($path)
    {
        $vendor = strtolower(basename(dirname($path)));
        $slug   = strtolower(substr(basename($path), 0, strpos(basename($path), '-')));
        $type   = strtolower(substr(basename($path), strpos(basename($path), '-') + 1));

        return "{$vendor}.{$type}.{$slug}";
    }

}
