<?php

namespace Pyro\Platform\Workflow;

use Crvs\RequesterRoleTypeExtension\Request\Contract\RequestInterface;
use Illuminate\Support\Collection;
use Laradic\Support\Dot;

class WorkflowManager
{
    /** @var \Closure[] */
    protected $extensions = [];

    /**
     * @param       $slug
     * @param \Closure $builderSetup
     *
     * @return void
     */
    public function extend($slug, \Closure $builderSetup)
    {
        $this->extensions[$slug] = $builderSetup;
    }

    public function get($slug)
    {
        return $this->extensions[$slug];
    }

    public function build($slug, $item, $itemId)
    {
        /** @var WorkflowBuilder $builder */
        $builder = resolve(WorkflowBuilder::class);
        $setupBuilder = $this->get($slug);
        $builder
            ->setSlug($slug)
            ->setItem($item, $itemId);
        app()->call($setupBuilder, compact('builder'));
        $workflow = $builder->build();
        return $workflow;
    }
}
