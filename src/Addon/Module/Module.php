<?php

namespace Pyro\Platform\Addon\Module;

class Module extends \Anomaly\Streams\Platform\Addon\Module\Module
{

    /** @var string */
    protected $parent = null;

    public function getParent(): string
    {
        return $this->parent;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    public function hasParent()
    {
        return $this->parent !== null;
    }
}
