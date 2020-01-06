<?php

namespace Pyro\Platform\Component;

use Illuminate\Support\Collection;

/**
 * @extends Collection<Prop>
 */
class PropCollection extends Collection
{
    /** @var array<string,Prop> */
    protected $items = [];

    public function createProp($name, $value = null, $binding = 'v-bind')
    {
        $prop              = new Prop();
        $prop[ 'name' ]    = $name;
        $prop[ 'value' ]   = $value;
        $prop[ 'binding' ] = $binding;
        $this->put($name, $prop);
        return $prop;
    }

    public function set($name, $value)
    {
        $this->setPropValue($name, $value);
        return $this;
    }

    public function get($name, $default = null)
    {
        return $this->getPropValue($name, $default);
    }

    public function setPropValue($name, $value)
    {
        if ($this->hasProp($name)) {
            $prop = $this->getProp($name);
        } else {
            $prop = $this->createProp($name);
        }
        $prop->setValue($value);
        return $this;
    }

    public function getPropValue($name, $default = null)
    {
        return $this->getProp($name)->getValue($default);
    }

    public function getProp($name)
    {
        return $this->items[ $name ];
    }

    public function hasProp($name)
    {
        return array_key_exists($name, $this->items);
    }

    public function setProp($name, $prop = null)
    {
        if ($prop === null) {
            if ($name instanceof Prop) {
                $prop = $name;
                $name = $prop->getBinding();
            }
        }
    }
}
