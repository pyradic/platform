<?php

namespace Pyro\Platform\Workflow;

use Pyro\Platform\Workflow\Concerns\HasItem;
use Pyro\Platform\Workflow\Concerns\HasState;
use Pyro\Platform\Workflow\Concerns\HasSteps;
use Pyro\Platform\Workflow\Concerns\HasTransitions;

class Workflow extends Base
{
    use HasTransitions;
    use HasSteps;
    use HasState;
    use HasItem;

    /** @var \Pyro\Platform\Workflow\Transition */
    protected $startTransition;

    public function __construct(
//        string $slug,
        StepCollection $steps,
        TransitionCollection $transitions,
        State $state
    )
    {
//        $this->slug        = $slug;
        $this->steps       = $steps;
        $this->transitions = $transitions;
        $this->state       = $state;
    }

    public function handle($transition)
    {
        if ( ! $this->can($transition)) {
            return false;
        }

        $transition = $this->transition($transition);
        return $transition->handle();
    }

    public function can($transition)
    {
        $transition = $this->transition($transition);
        if ($transition->getFrom()->getSlug() !== $this->currentStep()->getSlug()) {
            return false;
        }
        if ( ! $this->steps->has($transition->getTo()->getSlug())) {
            return false;
        }
        return $transition->validate();
    }

    public function currentStep()
    {
        return $this->step($this->state->getCurrentStep());
    }

    public function setCurrentStep($step)
    {
        $this->state
            ->setCurrentStep($step)
            ->save();
        return $this;
    }

    public function state($key, $default = null)
    {
        return $this->state->get($key, $default);
    }

    public function stateSet($key, $value, $save = false)
    {
        $this->state->set($key, $value);
        if ($save) {
            $this->state->save();
        }
        return $this;
    }

    public function saveState()
    {
        $this->state->save();
        return $this;
    }

    /** @var TransitionCollection|TransitionInterface[] */
    public $transitions;

    public function addTransition(TransitionInterface $transition)
    {
        $this->transitions->put($transition->getSlug(), $transition);
        return $transition;
    }

    public function getTransition($slug): TransitionInterface
    {
        return $this->transitions->get($slug);
    }

    public function transition($slug)
    {
        return $this->getTransition($slug);
    }

    public function getTransitions()
    {
        return $this->transitions;
    }

    public function setTransitions($transitions = [])
    {
        $this->transitions = $this->createTransitionCollection($transitions);
        return $this;
    }

    public function createTransitionCollection($transitions = [])
    {
        return TransitionCollection::wrap($transitions);
    }

    protected function resolveTransition($transition)
    {
        if(!$transition instanceof TransitionInterface){
            return $this->transition($transition);
        }
        return $transition;
    }
}
