<?php

namespace Pyro\Platform\Workflow;

use Illuminate\Http\Response;
use Pyro\Platform\Workflow\Aware\StateAwareInterface;
use Pyro\Platform\Workflow\Aware\TransitionAwareInterface;

class TransitionResult implements TransitionAwareInterface, StateAwareInterface
{
    const TYPE_RESPONSE = 'response';
    const TYPE_SUCCESS = 'success';

    /** @var string */
    public $type;

    /** @var Response */
    public $response;

    public $errors = [];

    /** @var \Pyro\Platform\Workflow\Transition */
    public $transition;

    /** @var State */
    public $state;

    public function isResponse()
    {
        return $this->response !== null;
    }

    public function hasErrors()
    {
        return count($this->errors) > 0;
    }

    public function setTransition($transition)
    {
        $this->transition = $transition;
        return $this;
    }

    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }


}
