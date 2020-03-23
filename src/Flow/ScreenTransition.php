<?php

namespace Pyro\Platform\Flow;

use Symfony\Component\Workflow\Transition;

class ScreenTransition extends Transition
{
    /** @var string|\Closure */
    protected $screen;

    public function getScreen()
    {
        return $this->screen;
    }

    public function setScreen($screen)
    {
        $this->screen = $screen;
        return $this;
    }

    public static function register(ScreenTransition $transition, array $config)
    {
        return;
    }



}
