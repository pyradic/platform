<?php

namespace Pyro\Platform\Workflow;

use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;
use Closure;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use Pyro\Platform\Workflow\Aware\AwareProvider;
use Pyro\Platform\Workflow\Aware\WorkflowAwareInterface;
use Pyro\Platform\Workflow\Command\BuildWorkflowFromArray;
use Pyro\Platform\Workflow\Command\SetupWorkflowRouting;

class Workflow
{
    /** @var string */
    public $slug;

    /** @var array array */
    public $supports = [];

    /** @var PlaceCollection|\Pyro\Platform\Workflow\Place[] */
    public $places;

    /** @var TransitionCollection|\Pyro\Platform\Workflow\Transition[] */
    public $transitions;

    /** @var Closure */
    public $resolve_place;

    /** @var StateStore */
    public $store;

    /** @var Collection */
    public $routing;

    /** @var Collection|\Illuminate\Routing\Route[] */
    public $routes;

    /** @var \Anomaly\Streams\Platform\Addon\Addon|null */
    public $addon;

    /** @var \Pyro\Platform\Workflow\Aware\AwareProvider */
    public $provider;

    public function __construct($slug)
    {
        $this->slug        = $slug;
        $this->provider    = new AwareProvider([
            WorkflowAwareInterface::class => $this,
        ]);
        $this->store       = resolve(StateStore::class, [ 'workflow' => $this ]);
        $this->places      = $this->provider->provide(PlaceCollection::make());
        $this->transitions = $this->provider->provide(TransitionCollection::make());
        $this->routing     = Collection::make();
    }

    /**
     * @param string           $slug
     * @param array|Collection $data
     *
     * @return static
     */
    public static function fromArray($slug, $data = [])
    {
        return dispatch_now(new BuildWorkflowFromArray($slug,$data));
    }

    public function handle(EntryInterface $subject, string $transactionSlug)
    {
        // check $this->supports to contain a instanceof $subject
        $transition = $this->transitions[ $transactionSlug ];
        $state       = $this->store->getOrCreate($subject);
        $result      = $transition->handle($state);
        return $result;
    }

    public function getBase64Slug()
    {
        return rtrim(base64_encode($this->slug), '=');
    }

    public function getTransitionHref($transition, $queryData = [])
    {
        $params = request()->route()->parameters();
        $href  = route($this->routes[ 'transition' ]->getName(), $params);
        $query = http_build_query(array_merge([
            'workflow'   => $this->getBase64Slug(),
            'transition' => $transition,
            'base64'     => true,
        ], $queryData));
        return $href . '?' . $query;
    }

}
