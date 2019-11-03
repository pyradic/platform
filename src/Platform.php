<?php

namespace Pyro\Platform;

use ArrayAccess;
use Collective\Html\HtmlBuilder;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Laradic\Support\Dot;
use Pyro\Platform\Webpack\Webpack;

class Platform implements ArrayAccess
{
    /** @var \Pyro\Platform\Webpack\Webpack */
    protected $webpack;

//    /** @var array|string[] */
//    protected $providers = [];
//
//
//    protected $scripts;
//
//    /** @var \Illuminate\Support\Collection */
//    protected $styles;

    /** @var \Illuminate\Contracts\Foundation\Application */
    protected $app;

    /** @var \Laradic\Support\Dot */
    protected $data;

    /** @var \Laradic\Support\Dot */
    protected $config;

    /** @var bool */
    protected $preventBootstrap = false;

    /** @var \Illuminate\Support\Collection|\Pyro\Platform\Webpack\WebpackAddonEntry[] */
    protected $entries;

    /** @var \Collective\Html\HtmlBuilder */
    protected $html;

    public function __construct(
        Application $app,
        Webpack $webpack,
        HtmlBuilder $html
    )
    {
        $this->app     = $app;
        $this->webpack = $webpack;
        $this->html    = $html;

        $this->config  = new Dot();
        $this->entries = new Collection();
        $this->data    = new Dot([
        ]);
    }

    public function getWebpack()
    {
        return $this->webpack;
    }

    public function getWebpackAddons()
    {
        return $this->webpack->getAddons();
    }

    public function getWebpackAddon($name)
    {
        return $this->webpack->getAddons()->findByName($name);
    }

    public function addWebpackEntry($name, $suffix = null)
    {
        $addon   = $this->findWebpackAddon($name);
        $entries = $addon->getEntries();
        $entry   = $suffix === null ? $entries->main() : $entries->suffix($suffix);
        $this->entries->put($entry->getName(), $entry);
        return $this;
    }

    public function findWebpackAddon($name)
    {
        if ($entry = $this->getWebpackAddons()->findByName($name)) {
            return $entry;
        }
        if ($entry = $this->getWebpackAddons()->findByComposerName($name)) {
            return $entry;
        }
        if ($entry = $this->getWebpackAddons()->findByStreamNamespace($name)) {
            return $entry;
        }
        throw new InvalidArgumentException("Could not find webpack addon with name '{$name}'");
    }

    public function preventBootstrap($value = true)
    {
        $this->preventBootstrap = $value;
        return $this;
    }

    public function shouldPreventBootstrap()
    {
        return $this->preventBootstrap || config('platform.cp_scripts.bootstrap') === false;
    }

    public function shouldntPreventBootstrap()
    {
        return ! $this->shouldPreventBootstrap();
    }

    public function set($key, $value = null)
    {
        $this->data->set($key, $value);
        return $this;
    }

    public function get($key, $default = null)
    {
        return $this->data->get($key, $default);
    }

    public function has($key)
    {
        return $this->data->has($key);
    }

    public function merge($key, $value = [])
    {
        $this->data->merge($key, $value);
        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function config()
    {
        return $this->config;
    }

    public function setConfig($config)
    {
        $this->config = $config;
        return $this;
    }

    public function getProviders()
    {
        $p = $this->entries->filter->hasProvider()->map->getProvider()->map(function ($provider, $exportName) {
            $namespace = $this->webpack->getNamespace();
            return "{$namespace}.{$exportName}.{$provider}";
        })->values()->implode(', ');

        return $p;
    }

    public function renderScripts()
    {
        $scripts = $this->entries->map->getScripts()->flatten()->map(function ($script) {
            return $this->html->script($script);
        });
        return $scripts;
    }

    public function offsetExists($offset)
    {
        return $this->data->has($offset);
    }

    public function offsetGet($offset)
    {
        return $this->data->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        return $this->data->set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        return $this->data->forget($offset);
    }
}
//    public function addScript(string $name, string $entrySuffix = null)
//    {
//        $addon = $this->getWebpackAddon($name);
//        $this->scripts->push(compact('name', 'entrySuffix', 'addon'));
//        return $this;
//    }
//
//    public function renderScripts()
//    {
//        foreach ($this->scripts as $entry) {
//            $name        = $entry[ 'name' ];
//            $entrySuffix = $entry[ 'entrySuffix' ];
//            /** @var \Pyro\Platform\Webpack\WebpackAddon $addon */
//            $addon       = $entry[ 'addon' ];
//            $scripts = $addon->getScripts()->map(function($script) use ($addon){
//                return $this->webpack->getPublicPath() . $script;
//            });
//        }
//    }
//
//    public function addStyle(string $name, string $entrySuffix = null)
//    {
//        $addon = $this->getWebpackAddon($name);
//        $this->styles->push(compact('name', 'entrySuffix', 'addon'));
//        return $this;
//    }
//
//    public function addProvider($provider)
//    {
//        if ($provider instanceof AddonServiceProvider) {
//            $reflection = new ReflectionClass(get_class($provider));
//            $property   = $reflection->getProperty('addon');
//            $property->setAccessible(true);
//            $provider = $property->getValue($provider);
//        }
//        if ($provider instanceof Addon) {
//            //$this->addAddon($provider);
//            $addon      = $this->webpack->getAddons()->findByStreamNamespace($provider->getNamespace());
//            $exportName = last(explode('\\',$provider->getServiceProvider()));
//        } elseif (strpos($provider, '::') !== false) {
//            [ $name, $exportName ] = explode('::', $provider);
//            $addon = $this->webpack->getAddons()->findByName($name);
//        }
//        $namespace         = $this->webpack->getNamespace();
//        $provider          = "{$namespace}.{$addon->getEntryName()}.{$exportName}";
//        $this->providers[] = $provider;
//
//        return $this;
//    }
