<?php

namespace Pyro\Platform\Ui\ControlPanel\Component;

class NavigationLink extends \Anomaly\Streams\Platform\Ui\ControlPanel\Component\Navigation\NavigationLink
{
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

    public function getUrl()
    {
        return $this->url ?? $this->getAttributes()['href'];
    }

}
