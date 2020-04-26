<?php

namespace Pyro\Platform\Ui\Traits;

trait HasEnabledAttribute
{
    /** @var bool */
    protected $enabled = true;

    public function isEnabled()
    {
        return $this->enabled;
    }

    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }


}
