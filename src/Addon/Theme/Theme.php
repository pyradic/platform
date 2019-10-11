<?php

namespace Pyro\Platform\Addon\Theme;

class Theme extends \Anomaly\Streams\Platform\Addon\Theme\Theme
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
