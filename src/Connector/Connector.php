<?php

namespace Pyradic\Platform\Connector;

abstract class Connector
{
    /** @var \CRVS\Application */
    protected static $legacyApplication;

    /** @var string */
    protected $name;

    public function getLegacyApplication(): \CRVS\Application
    {
        if (static::$legacyApplication === null) {
            static::$legacyApplication = require $this->getBootstrapPath();
        }
        return static::$legacyApplication;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function path(...$parts)
    {
        return path_join(base_path(), 'applications', $this->name, ...$parts);
    }


    abstract protected function getBootstrapPath(): string;
}
