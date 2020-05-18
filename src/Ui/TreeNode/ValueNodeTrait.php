<?php

namespace Pyro\Platform\Ui\TreeNode;

use Illuminate\Support\Traits\ForwardsCalls;

/**
 * @mixin \Pyro\Platform\Ui\TreeNode\NodeTrait
 */
trait ValueNodeTrait
{
    use NodeTrait {
        __call as __callMacro;
    }
    use ForwardsCalls;

    public function offsetExists($offset)
    {
        return $this->getValue()->offsetExists($offset);
    }

    public function offsetGet($offset)
    {
        return $this->getValue()->offsetGet($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->getValue()->offsetSet($offset, $value);
    }

    public function offsetUnset($offset)
    {
        $this->getValue()->offsetUnset($offset);
    }

    public function __get($name)
    {
        return $this->getValue()->{$name};
    }

    public function __set($name, $value)
    {
        return $this->getValue()->{$name} = $value;
    }

    public function __call($method, $parameters)
    {
        if(static::hasMacro($method)){
            return $this->__callMacro($method, $parameters);
        }
        return $this->forwardCallTo($this->getValue(), $method, $parameters);
    }
}
