<?php

namespace Pyradic\Platform\Connector;

use Illuminate\Contracts\Container\Container;

class ConnectorFactory
{
    /** @var \Pyradic\Platform\Connector\Connector */
    protected $instance;

    /** @var \Illuminate\Contracts\Container\Container */
    protected $container;

    /**
     * ConnectorFactory constructor.
     *
     * @param \Illuminate\Contracts\Container\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * boot method
     *
     * @return \CRVS\Application
     */
    public function boot()
    {
        $connector = $this->getConnector();
        $legacy    = $connector->getLegacyApplication();
        if ($legacy->isBooted()) {
            return $legacy;
        }
        if (method_exists($connector, 'boot') === false) {
            throw new \BadMethodCallException("Connector [{$this->getConnectorName()}] should have a boot method");
        }
        $this->container->call([ $connector, 'boot' ]);
        return $legacy;
    }


    /**
     * getConnector method
     *
     * @return \Pyradic\Platform\Connector\Connector
     */
    protected function getConnector()
    {
        if ($this->instance === null) {
            $class          = $this->getConnectorClassName();
            $this->instance = $this->container->make($class);
            $this->instance->setName($this->getConnectorName());
        }
        return $this->instance;
    }

    protected function getConnectorName()
    {
        return config('crvs.applications.connector');
    }

    protected function getConnectorClassName()
    {
        return config('crvs.applications.connectors.' . $this->getConnectorName());
    }
}
