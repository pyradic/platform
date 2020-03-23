<?php

namespace Pyro\Platform\Workflow\Concerns;

trait HasState
{

    /** @var \Pyro\Platform\Workflow\State */
    protected $state;

    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }
//
//    public function state($key, $default = null)
//    {
//        return $this->state->get($key, $default);
//    }
//
//    public function stateSet($key, $default = null)
//    {
//        return $this->state->get($key, $default);
//    }
}
