<?php

namespace Pyro\Platform\Workflow;

use Pyro\Platform\Workflow\Concerns\HasWorkflow;

class Step extends Base implements StepInterface
{
    use HasWorkflow;

    protected $final = false;

    /**
     * Step constructor.
     *
     * @param \Pyro\Platform\Workflow\Workflow $workflow
     * @param string                           $slug
     */
    public function __construct(string $slug)
    {
        $this->slug     = $slug;
    }

    public function isFinal()
    {
        return $this->final;
    }

    public function setFinal($final = true)
    {
        $this->final = $final;
        return $this;
    }

}
