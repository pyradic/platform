<?php

namespace Pyro\Platform\Component;

trait SharesStore
{
    protected function getStore()
    {
        return Store::getInstance();
    }

    protected function shared($key, $default = null)
    {
        return $this->getStore()->get($key, $default);
    }

    protected function share($key, $value)
    {
        $this->getStore()->set($key, $value);
        return $this;
    }
}
