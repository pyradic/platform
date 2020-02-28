<?php

namespace Pyro\Platform\Ui\ControlPanel\Component;

class Shortcut extends \Anomaly\Streams\Platform\Ui\ControlPanel\Component\Shortcut\Shortcut
{
    protected $children = [];

    protected $type = 'default';

    public function __construct()
    {
        return;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function setChildren($children)
    {
        $this->children = $children;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

}
