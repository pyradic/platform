<?php

namespace Pyro\Platform\Workflow;

use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use Pyro\Platform\Workflow\Command\BuildWorkflowFromArray;

class WorkflowManager
{
    /** @var \Illuminate\Support\Collection|\Pyro\Platform\Workflow\Workflow[] */
    protected $workflows;

    /** @var \Illuminate\Support\Collection  */
    protected $drafts;

    /**
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    public function __construct(Collection $workflows, Collection $drafts, Router $router)
    {
        $this->workflows = $workflows;
        $this->drafts = $drafts;
        $this->router = $router;
    }

    public function addFromArray($slug, array $data)
    {
        if (data_get($data, 'draft', false) === true) {
            $this->drafts[ $slug ] = $data;
        } else {
            $this->workflows[ $slug ] = dispatch_now(new BuildWorkflowFromArray($slug, $data));
            $href= $this->workflows[ $slug ]->routing['href'];
            $this->router->any($href, [
                'as' => $slug,
                'uses' => WorkflowController::class . '@transition'
            ]);
        }
        return $this;
    }

    public function add($slug, $workflow)
    {
        $this->workflows->put($slug, $workflow);
        return $this;
    }

    public function getDraft($slug, array $overrides = [])
    {
        if ( ! $this->drafts->has($slug)) {
            throw new \InvalidArgumentException("Draft [{$slug}] not found");
        }
        $draft = $this->drafts->get($slug);
        unset($draft['draft']);
        return array_replace_recursive($draft, $overrides);
    }

    /**
     * @param $slug
     *
     * @return \Pyro\Platform\Workflow\Workflow
     */
    public function get($slug)
    {
        return $this->workflows->get($slug);
    }

    public function has($slug)
    {
        return $this->workflows->has($slug);
    }
}
