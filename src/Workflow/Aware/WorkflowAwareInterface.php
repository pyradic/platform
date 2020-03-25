<?php

namespace Pyro\Platform\Workflow\Aware;

use Pyro\Platform\Workflow\Workflow;

interface WorkflowAwareInterface
{
    public function setWorkflow(Workflow $workflow);
}
