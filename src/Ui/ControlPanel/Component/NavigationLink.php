<?php

namespace Pyro\Platform\Ui\ControlPanel\Component;

class NavigationLink extends \Anomaly\Streams\Platform\Ui\ControlPanel\Component\Navigation\NavigationLink
{

    use ComponentTrait;

    protected $children = [];

    public function getChildren()
    {
        return $this->children;
    }

    public function setChildren($children)
    {
        $this->children = $children;
        return $this;
    }

    protected $key;

    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    public function getKey()
    {
        return $this->key;
    }

}
