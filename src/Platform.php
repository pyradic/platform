<?php

namespace Pyro\Platform;

use Anomaly\Streams\Platform\Addon\Addon;
use Anomaly\Streams\Platform\Addon\AddonServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Collection;
use Laradic\Support\Dot;
use Pyro\Platform\Webpack\Webpack;
use ReflectionClass;

class Platform
{
    /** @var \Pyro\Platform\Webpack\Webpack */
    protected $webpack;

    /** @var array|string[] */
    protected $providers = [];

    protected $scripts;

    /** @var \Illuminate\Support\Collection */
    protected $styles;

    /** @var \Illuminate\Contracts\Foundation\Application */
    protected $app;

    /** @var \Laradic\Support\Dot */
    protected $data;

    /** @var \Laradic\Support\Dot */
    protected $config;

    protected $preventBootstrap = false;

    public function __construct(Application $app, Webpack $webpack)
    {
        $this->app     = $app;
        $this->webpack = $webpack;
        $this->data    = new Dot();
        $this->config  = new Dot();
        $this->scripts = new Collection();
        $this->styles  = new Collection();
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

    public function addScript(string $name, string $entrySuffix = null)
    {
        $addon = $this->getWebpackAddon($name);
        $this->scripts->push(compact('name', 'entrySuffix', 'addon'));
//        $scripts = $addon->getScripts()->map(function($script) use ($addon) {
//            return $this->webpack->getPublicPath() . $script;
//        });
        return $this;
    }

    public function renderScripts()
    {
        foreach ($this->scripts as $entry) {
            $name        = $entry[ 'name' ];
            $entrySuffix = $entry[ 'entrySuffix' ];
            /** @var \Pyro\Platform\Webpack\WebpackAddon $addon */
            $addon       = $entry[ 'addon' ];
            $scripts = $addon->getScripts()->map(function($script) use ($addon){
                return $this->webpack->getPublicPath() . $script;
            });
        }
    }

    public function addStyle(string $name, string $entrySuffix = null)
    {
        $addon = $this->getWebpackAddon($name);
        $this->styles->push(compact('name', 'entrySuffix', 'addon'));
        return $this;
    }

    public function addProvider($provider)
    {
        if ($provider instanceof AddonServiceProvider) {
            $reflection = new ReflectionClass(get_class($provider));
            $property   = $reflection->getProperty('addon');
            $property->setAccessible(true);
            $provider = $property->getValue($provider);
        }
        if ($provider instanceof Addon) {
            $this->addAddon($provider);
            $addon      = $this->webpack->getAddons()->findByStreamNamespace($provider->getNamespace());
            $exportName = last(explode('\\',$provider->getServiceProvider()));
        } elseif (strpos($provider, '::') !== false) {
            [ $name, $exportName ] = explode('::', $provider);
            $addon = $this->webpack->getAddons()->findByName($name);
        }
        $namespace         = $this->webpack->getNamespace();
        $provider          = "{$namespace}.{$addon->getEntryName()}.{$exportName}";
        $this->providers[] = $provider;

        return $this;
    }

    public function addAddon(Addon $streamAddon)
    {
        $webpackAddon = $this->webpack->getAddons()->findByStreamNamespace($streamAddon->getNamespace());
        if ( ! $webpackAddon->hasStreamAddon()) {
            $webpackAddon->setStreamAddon($streamAddon);
        }
        return $this;
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

    public function getConfig()
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
        return $this->providers;
    }
}
