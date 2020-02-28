<?php

namespace Pyro\Platform\Ui\ControlPanel\Component;

class Button extends \Anomaly\Streams\Platform\Ui\Button\Button
{

    use ComponentTrait;

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

    /**
     * Get the title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getText();
    }

    /**
     * Set the title.
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->setText($title);
    }
}
