<?php

namespace Pyro\Platform\Command;

use Anomaly\Streams\Platform\Addon\Addon;
use Anomaly\Streams\Platform\Addon\AddonCollection;
use Anomaly\Streams\Platform\Support\Configurator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;

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
        /** @var \Illuminate\View\FileViewFinder $finder */
        $finder=$factory->getFinder();
        $overridePaths = glob($this->addon->getPath('resources/addons/*/*'), GLOB_NOSORT);
        foreach ($overridePaths as $overridePath) {
            $namespace   = $this->getAddonNamespace($overridePath);
            if(!$addons->has($namespace)){
                continue;
            }
            $targetAddon = $addons->get($namespace);
            if ($fs->exists($viewPath = $overridePath . '/views')) {
                $hints = $finder->getHints()[$namespace];
                $finder->addNamespace('original/' . $namespace, $hints);
                $finder->prependNamespace($namespace, $viewPath);
            }
            if ($fs->exists($configPath = $overridePath . '/config')) {
                $configurator->addNamespaceOverrides($targetAddon->getNamespace(), $configPath);
            }
            if($fs->exists($langPAth = $overridePath . '/lang')){
                trans();
            }
        }

        // streams::
        $streamsOverridePath = $this->addon->getPath('resources/streams');
        if ($fs->exists($streamsOverridePath)) {
            if ($fs->exists($viewPath = $streamsOverridePath . '/views')) {
                $namespace = 'streams';
                $hints = $finder->getHints()[$namespace];

                // backup the original namespace hints into another namespace.
                // this allows overriding views to still include the 'parent' by prefixing the
                // view name with 'original/' like: original/pyrocms.theme.accelerant::partials.metadata
                $finder->addNamespace('original/' . $namespace, $hints);
                $finder->prependNamespace($namespace, $viewPath);
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
