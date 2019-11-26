<?php

namespace Pyro\Platform\Ui\ControlPanel\Component;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use Laradic\Support\Traits\ArrayAccessibleProperties;

class Section extends \Anomaly\Streams\Platform\Ui\ControlPanel\Component\Section\Section implements Arrayable, ArrayAccess
{

    use ArrayableComponent;
    use ArrayAccessibleProperties;

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

    /** @var \Pyro\Platform\Ui\ControlPanel\Component\NavigationLink */
    protected $parent;

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
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
