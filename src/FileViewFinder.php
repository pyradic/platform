<?php

namespace Pyro\Platform;

use Anomaly\Streams\Platform\Addon\Addon;
use Anomaly\Streams\Platform\Addon\AddonCollection;
use Anomaly\Streams\Platform\Addon\Theme\ThemeCollection;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class FileViewFinder extends \Illuminate\View\FileViewFinder
{
    /** @var \Anomaly\Streams\Platform\Addon\AddonCollection */
    protected $addons;

    /** @var \Illuminate\Support\Collection */
    protected $overrides;

    public function __construct(Filesystem $files, array $paths, array $extensions = null)
    {
        $this->setAddons(new AddonCollection());
        parent::__construct($files, $paths, $extensions);
    }

    // @todo remove
    public function setAddons(AddonCollection $addons)
    {
        $this->addons    = $addons;
        $this->overrides = collect();
        $this->addons->each(function (Addon $addon) {
            if ($this->files->isDirectory($addon->getPath('resources/views/addons'))) {
                foreach ($this->files->glob($addon->getPath('resources/views/addons/*/*/*')) as $path) {
                    $namespace = collect(explode('/', $path))
                        ->reverse()
                        ->slice(0, 3)
                        ->reverse()
                        ->implode('.');
                    $this->override($namespace, $path, $addon);
                }
            }
        });
    }

    // @todo remove
    protected function override($namespace, $path, $sourceAddon = null)
    {
        $targetAddon = $this->addons->get($namespace);
        $type        = $targetAddon->getType();

        $overrides = $this->overrides->get($namespace, collect());
        $overrides->put($sourceAddon->getNamespace(), compact('namespace', 'path', 'targetAddon', 'sourceAddon', 'type'));
        $this->overrides->put($namespace, $overrides);

        $this->prependNamespace($namespace, $path);
    }

    // @todo remove
    protected $pathOverrides = [];

    // @todo remove
    public function addPathOverride($namespace, $hints)
    {
        if ( ! array_key_exists($namespace, $this->pathOverrides)) {
            $this->pathOverrides[ $namespace ] = [];
        }
        $this->pathOverrides[ $namespace ][] = $this->getHints()[ $namespace ];
    }

    public function find($name)
    {
        if (Str::startsWith($name, 'parent::')) { // @todo remove
            list($parent, $namespace, $view) = explode('::',$name);
            return $this->findInPaths($view, $this->pathOverrides[$namespace][0]);
        }
        if (Str::startsWith($name, 'theme::')) {
            $theme = resolve(ThemeCollection::class)->current();
            $name  = Str::replaceFirst('theme::', $theme->getNamespace() . '::', $name);
        }
        return parent::find($name);
    }

    // @todo remove
    public function find2($name)
    {
        if (Str::startsWith($name, 'theme::')) {
            $theme = resolve(ThemeCollection::class)->current();
            if ($theme) {
                $overrides = $this->overrides
                    ->map->where('type', 'theme')
                    ->flatten(1)
                    ->where('namespace', $theme->getNamespace());
                $paths     = $overrides->keyBy('sourceAddon.namespace')->mapWithKeys(function ($value, $key) {
                    return [ $key => $value[ 'path' ] ];
                });
                if ($paths->isNotEmpty()) {
                    [ $_namespace, $view ] = $this->parseNamespaceSegments($name);
                    foreach ($paths as $namespace => $path) {
                        try {

                            $this->findInPaths($view, [ $path ]);
                            $override = $overrides->keyBy('sourceAddon.namespace')->get($namespace);
                            return $this->find($namespace . '::addons/' . str_replace('.', '/', $override[ 'namespace' ]) . '/' . $view);
                        }
                        catch (\InvalidArgumentException $e) {

                        }
                    }
                }
            }
        }
        return parent::find($name);
    }
}
