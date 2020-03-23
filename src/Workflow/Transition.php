<?php

namespace Pyro\Platform\Workflow;

use Pyro\Platform\Workflow\Concerns\HasScreen;
use Pyro\Platform\Workflow\Concerns\HasWorkflow;

class Transition extends Base implements TransitionInterface
{
    use HasWorkflow;
    use HasScreen;

    /** @var Workflow */
    protected $workflow;

    /** @var StepInterface */
    protected $from;

    /** @var StepInterface */
    protected $to;

    /** @var \Closure */
    protected $handler;

    public function __construct(string $slug, StepInterface $from, StepInterface $to, $handler = null)
    {
        $this->slug    = $slug;
        $this->from    = $from;
        $this->to      = $to;
        $this->handler = $handler;
    }

    public function handle()
    {
        if ($this->hasScreen()) {
            $response = $this->renderScreen();
        }

        $this->getWorkflow()->getState()->setCurrentStep($this->to);
    }

    public function renderScreen()
    {
        return $this->resolveScreen($this)->render();
    }

    public function validate()
    {
        return $this->resolveHandler()->validate();
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }

    public function getHandler()
    {
        return $this->handler;
    }

    public function setHandler($handler)
    {
        $this->handler = $handler;
        return $this;
    }

    public function resolveHandler(): TransitionHandler
    {
        if ($this->handler instanceof \Closure) {
            $handler = app()->call($this->handler, [ 'transition' => $this ]);
        } elseif (is_string($this->handler)) {
            $handler = app()->make($this->handler, [ 'transition' => $this ]);
        }
        if ( !isset($handler) || ! $handler instanceof TransitionHandler) {
            throw new \RuntimeException("Not a valid transition handler for transition '{$this->getSlug()}' in workflow '{$this->getWorkflow()->getSlug()}'");
        }

        return $handler;
    }

}
