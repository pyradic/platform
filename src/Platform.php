<?php

namespace Pyradic\Platform;

use Laradic\Support\Dot;

class Platform
{
    /** @var \Laradic\Support\Dot */
    protected $data;

    /** @var \Laradic\Support\Dot */
    protected $config;

    /** @var string[] */
    protected $providers = [];

    public function __construct(array $data = [], array $config = [], array $providers = [])
    {
        $this->data   = new Dot($data);
        $this->config = new Dot($config);
        $this->providers = $providers;
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


}
