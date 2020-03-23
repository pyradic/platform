<?php

namespace Pyro\Platform\Workflow\Concerns;

use Pyro\Platform\Workflow\Step;
use Pyro\Platform\Workflow\StepCollection;
use Pyro\Platform\Workflow\StepInterface;

trait HasSteps
{

    /** @var \Pyro\Platform\Workflow\StepCollection|StepInterface[] */
    public $steps;

    public function addStep(StepInterface $step)
    {
        $this->steps->put($step->getSlug(), $step);
        return $step;
    }

    public function getStep($slug): StepInterface
    {
        return $this->steps->get($slug);
    }

    public function step($slug)
    {
        return $this->getStep($slug);
    }

    public function getSteps()
    {
        return $this->steps;
    }

    public function setSteps($steps = [])
    {
        $this->steps = $this->createStepCollection($steps);
        return $this;
    }

    public function createStepCollection($steps = [])
    {
        return StepCollection::wrap($steps);
    }
}
