<?php

namespace Pyro\Platform\Workflow;

use Illuminate\Support\Collection;
use Pyro\Platform\Workflow\Aware\WorkflowAwareInterface;

class PlaceCollection extends Collection implements WorkflowAwareInterface
{
    /** @var \Pyro\Platform\Workflow\Workflow */
    public $workflow;

    public function add($item)
    {
        if ( ! $item instanceof Place) {
            $item = new Place($item);
        }
        $item->workflow = $this->workflow;
        $this->put($item->slug, $item);
        return $this;
    }

    public function setWorkflow(Workflow $workflow)
    {
        $this->workflow = $workflow;
        return $this;
    }


}
