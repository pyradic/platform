<?php

namespace Pyro\Platform\Workflow;

use Pyro\Platform\Workflow\Concerns\HasWorkflow;

abstract class AbstractTransitionHandler implements TransitionHandler
{
    use HasWorkflow;

    /** @var \Pyro\Platform\Workflow\TransitionInterface */
    protected $transition;

    public function __construct(TransitionInterface $transition)
    {
        $this->transition = $transition;
    }


    public function getTransition()
    {
        return $this->transition;
    }

    public function setTransition($transition)
    {
        $this->transition = $transition;
        return $this;
    }

    public function validate(array $data = [])
    {
        return true;
    }

    public function transit()
    {
    }
}
