<?php

namespace Pyro\Platform\Workflow\Concerns;

use Pyro\Platform\Workflow\Transition;
use Pyro\Platform\Workflow\TransitionCollection;
use Pyro\Platform\Workflow\TransitionInterface;

trait HasTransitions
{


    /** @return \Pyro\Platform\Workflow\Workflow */
    abstract public function getWorkflow();
}
