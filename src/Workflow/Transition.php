<?php

namespace Pyro\Platform\Workflow;

use Closure;
use Illuminate\Http\Response;
use Pyro\Platform\Workflow\Aware\AwareProvider;
use Pyro\Platform\Workflow\Aware\StateAwareInterface;
use Pyro\Platform\Workflow\Aware\TransitionAwareInterface;
use Pyro\Platform\Workflow\Aware\WorkflowAwareInterface;

class Transition implements WorkflowAwareInterface
{
    /** @var \Pyro\Platform\Workflow\Workflow */
    public $workflow;

    /** @var string */
    public $slug;

    /** @var string */
    public $from;

    /** @var string */
    public $to;

    /** @var Closure */
    public $handler;

    /** @var string */
    public $screen;

    public function __construct($slug, $from, $to, ?Closure $handler = null, ?string $screen = null)
    {
        $this->slug    = $slug;
        $this->from    = $from;
        $this->to      = $to;
        $this->handler = $handler;
        $this->screen  = $screen;
    }

    public function handle(State $state)
    {
        $provider = new AwareProvider([
            StateAwareInterface::class      => $state,
            TransitionAwareInterface::class => $this,
            WorkflowAwareInterface::class   => $this->workflow,
        ]);
        $result   = new TransitionResult();
        $provider->provide($result);
        if ( ! $this->validate($state)) {
            $result->errors[] = 'validation error';
            return $result;
        }

        if ($this->screen) {
            $screen = app()->make($this->screen);
            if ( ! $screen instanceof Screen) {
                throw new \RuntimeException("Class {$this->screen} does not implement " . Screen::class);
            }
            $provider->provide($screen);

            if ($this->handler) {
                $params                 = compact('state', 'screen', 'result');
                $params[ 'transition' ] = $this;
                $handlerResult          = app()->call($this->handler, $params);
                if ($handlerResult instanceof Response) {
                    $result->response = $handlerResult;
                } elseif ($handlerResult instanceof TransitionResult) {
                    $result = $handlerResult;
                }
            } else {
                $result->response = $screen->render();
            }
            return $result;
        }

        if ($this->handler) {
            $params                 = compact('state', 'result');
            $params[ 'transition' ] = $this;
            $result                 = app()->call($this->handler, $params);
            return $result;
        }
        return $result;
    }

    public function validate(State $state)
    {
        if ($state->place !== $this->from) {
            return false;
        }
        return true;
    }

    public function getFrom()
    {
        return $this->workflow->places[ $this->from ];
    }

    public function getTo()
    {
        return $this->workflow->places[ $this->to ];
    }

    public function setWorkflow($workflow)
    {
        $this->workflow = $workflow;
        return $this;
    }


}
