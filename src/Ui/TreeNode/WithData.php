<?php

namespace Pyro\Platform\Ui\TreeNode;

use Laradic\Support\Dot;

trait WithData
{

    /** @var \Laradic\Support\Dot */
    protected $data;

    public function set($key, $value)
    {
        $this->getData()->set($key, $value);
        return $this;
    }

    public function get($key, $default = null)
    {
        $this->getData()->get($key, $default);
        return $this;
    }

    public function has($key)
    {
        return $this->getData()->has($key);
    }

    public function getData()
    {
        if ($this->data === null) {
            if ($this->data instanceof Dot === false) {
                $this->setData(Dot::make());
            }
        }
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
}
