<?php

namespace Pyro\Platform\Ui\ControlPanel\Component;

class Section extends \Anomaly\Streams\Platform\Ui\ControlPanel\Component\Section\Section
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
        return $this->getAttributes()['href'];
    }
}
