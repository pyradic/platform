<?php

namespace Pyro\Platform\Event;

use Pyro\Platform\Platform;

class PlatformWillRender
{
    /** @var \Pyro\Platform\Platform */
    protected $platform;

    public function __construct(Platform $platform)
    {
        $this->platform = $platform;
    }

    public function getPlatform()
    {
        return $this->platform;
    }
}
