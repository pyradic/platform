<?php

namespace Pyro\Platform\Workflow;

use Illuminate\Support\Collection;
use Pyro\Platform\Workflow\Aware\WorkflowAwareInterface;

class TransitionCollection extends Collection implements WorkflowAwareInterface
{
    /** @var \Pyro\Platform\Workflow\Workflow */
    public $workflow;

    public function add($item)
    {
        if ( ! $item instanceof Transition) {
            $item = collect($item);
            $item = new Transition($item[ 'slug' ], $item[ 'from' ], $item[ 'to' ], $item[ 'handler' ], $item[ 'screen' ]);
        }
        $this->workflow->provider->provide($item);
        $this->put($item->slug, $item);
        return $this;
    }

    public function setWorkflow(Workflow $workflow)
    {
        $this->workflow = $workflow;
    }
}
