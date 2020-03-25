<?php

namespace Pyro\Platform\Workflow\Aware;

use Pyro\Platform\Workflow\State;

interface StateAwareInterface
{
    public function setState(State $state);
}
