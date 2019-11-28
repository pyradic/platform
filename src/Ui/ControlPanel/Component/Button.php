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
}
