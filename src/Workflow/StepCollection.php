<?php

namespace Pyro\Platform\Workflow;

use Illuminate\Support\Collection;

class StepCollection extends Collection
{
    /** @var Step[] */
    protected $items = [];
}
