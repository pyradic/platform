<?php

namespace Pyro\Platform\Workflow;

use Illuminate\Support\Collection;

class TransitionCollection extends Collection
{
    /** @var \Pyro\Platform\Workflow\Transition[] */
    protected $items = [];
}
