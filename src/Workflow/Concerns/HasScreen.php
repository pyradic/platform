<?php

namespace Pyro\Platform\Workflow\Concerns;

use Pyro\Platform\Workflow\Screen;
use Pyro\Platform\Workflow\TransitionInterface;

trait HasScreen
{

    /** @var string|\Pyro\Platform\Workflow\Screen */
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

    public function hasScreen()
    {
        return $this->screen !== null;
    }

    protected function resolveScreen(TransitionInterface $transition)
    {
        if ($this->screen instanceof Screen) {
            return $this->screen;
        }
        $this->screen = app()->build($this->screen);
        $this->screen->setTransition($transition);
        return $this->screen;
    }

    /** @return \Pyro\Platform\Workflow\Workflow */
    abstract public function getWorkflow();

}
