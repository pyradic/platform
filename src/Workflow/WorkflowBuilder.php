<?php

namespace Pyro\Platform\Workflow;

use Pyro\Platform\Workflow\Concerns\HasItem;
use Pyro\Platform\Workflow\Concerns\HasState;
use Pyro\Platform\Workflow\Concerns\HasSteps;
use Pyro\Platform\Workflow\Concerns\HasTransitions;
use Pyro\Platform\Workflow\Concerns\HasWorkflow;

class WorkflowBuilder extends Base
{
    use HasSteps;
    use HasTransitions;
    use HasState;
    use HasItem;
    use HasWorkflow;

    public function __construct(Workflow $workflow)
    {
        $this->workflow    = $workflow;
        $this->state       = new State();
        $this->steps       = $this->createStepCollection();
        $this->transitions = $this->createTransitionCollection();
    }

    public function build()
    {
        $workflow = $this->workflow
            ->setSlug($this->slug)
            ->setSteps($this->steps)
            ->setTransitions($this->transitions)
            ->setItem($this->item, $this->itemId)
            ->setState($this->state);

        $workflow->getState()
            ->setKey("workflows.{$workflow->getSlug()}.{$workflow->getItemId()}")
            ->load();

        return $workflow;
    }
}

