<?php

namespace Pyro\Platform\Ui\ControlPanel\Component;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use Laradic\Support\Traits\ArrayAccessibleProperties;

class Button extends \Anomaly\Streams\Platform\Ui\Button\Button implements Arrayable, ArrayAccess
{

    use ArrayableComponent;
    use ArrayAccessibleProperties;

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

    /** @var \Pyro\Platform\Ui\ControlPanel\Component\Section */
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


}
