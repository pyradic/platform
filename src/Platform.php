<?php

namespace Pyro\Platform;

use Illuminate\Support\Arr;
use Laradic\Support\Dot;

class Platform
{
    /** @var \Laradic\Support\Dot */
    protected $data;

    /** @var \Laradic\Support\Dot */
    protected $config;

    /** @var string[] */
    protected $providers = [];

    /** @var \Illuminate\Contracts\Console\Application */
    protected $app;

    protected $preventBootstrap = false;

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

    public function __construct(array $data = [], array $config = [], array $providers = [])
    {
        $this->app       = \Illuminate\Foundation\Application::getInstance();
        $this->data      = new Dot($data);
        $this->config    = new Dot($config);
        $this->providers = $providers;
    }

    public function set($key, $value=null)
    {
        $this->data->set($key, $value);
        return $this;
    }
    public function get($key, $default=null)
    {
        return $this->data->get($key, $default);
    }
    public function has($key)
    {
        return $this->data->has($key);
    }

    public function merge($key, $value = [])
    {
        $this->data->merge($key,$value);
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

    public function setProviders($providers)
    {
        $this->providers = $providers;
        return $this;
    }

    public function addProvider($provider)
    {
        $this->providers[] = $provider;
        return $this;
    }

    protected $publicScripts = [];

    protected $publicStyles = [];

    public function getPublicScripts()
    {
        return $this->publicScripts;
    }

    public function setPublicScripts(array $publicScripts)
    {
        $this->publicScripts = $publicScripts;
        return $this;
    }

    public function getPublicStyles()
    {
        return $this->publicStyles;
    }

    public function setPublicStyles(array $publicStyles)
    {
        $this->publicStyles = $publicStyles;
        return $this;
    }

    /**
     * @param string|string[] $publicScript
     *
     * @return $this
     */
    public function addPublicScript($publicScript)
    {
        $this->publicScripts = array_merge($this->publicScripts, Arr::wrap($publicScript));
        return $this;
    }

    /**
     * @param string|string[] $publicStyle
     *
     * @return $this
     */
    public function addPublicStyle($publicStyle)
    {
        $this->publicStyles = array_merge($this->publicStyles, Arr::wrap($publicStyle));
        return $this;
    }

    public function __toString()
    {
        return '';
    }

}

/*

addScript('pyro/



 */
