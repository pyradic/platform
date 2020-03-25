<?php

namespace Pyro\Platform\Workflow\Aware;

use Pyro\Platform\Workflow\Transition;

interface TransitionAwareInterface
{
    public function setTransition(Transition $transition);
}
