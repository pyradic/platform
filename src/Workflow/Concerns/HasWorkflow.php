<?php

namespace Pyro\Platform\Workflow\Concerns;

use Pyro\Platform\Workflow\Workflow;

trait HasWorkflow
{

    /** @var \Pyro\Platform\Workflow\Workflow */
    protected $workflow;

    public function setWorkflow($workflow)
    {
        $this->workflow = $workflow;
        return $this;
    }

    public function getWorkflow(): Workflow
    {
        return $this->workflow;
    }

}
