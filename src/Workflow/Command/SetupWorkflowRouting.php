<?php

namespace Pyro\Platform\Workflow\Command;

use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use Pyro\Platform\Workflow\Workflow;
use Pyro\Platform\Workflow\WorkflowController;

class SetupWorkflowRouting
{
    /** @var \Pyro\Platform\Workflow\Workflow */
    protected $workflow;

    /**
     * @var bool
     */
    protected $force;

    public function __construct(Workflow $workflow, $force = false)
    {
        $this->workflow = $workflow;
        $this->force    = $force;
    }

    public function handle(Router $router)
    {
        $workflow = $this->workflow;
        if ($workflow->routes instanceof Collection && $this->force === false) {
            return;
        }

        $workflow->routing = collect([
            'as'  => $workflow->slug,
            'uri' => 'admin/_workflow',
        ])->merge($workflow->routing);

        $routes = [];
        $c      = WorkflowController::class;
        foreach ([
                     'workflow/transition' => [ 'uses' => $c . '@transition', 'key' => 'transition' ],
                 ] as $uri => $route) {
            $route = collect($route);

            if ($workflow->routing->has('as')) {
                $route[ 'as' ] = $workflow->routing->get('as', '') . ($route->has('as') ? '.' . $route[ 'as' ] : '');
            }
            $uri = implode('/', [ $workflow->routing->get('uri'), $uri ]);

            $key         = $route->pull('key');
            $verb        = $route->pull('verb', 'any');
            $group       = $route->pull('group', []);
            $middleware  = $route->pull('middleware', []);
            $constraints = $route->pull('constraints', []);

            if ($addon = $this->workflow->addon) {
                $route->put('streams::addon', $addon->getNamespace());
            }

            $routes[ $key ] = $router->{$verb}($uri, $route->toArray())->where($constraints);
        }
        $workflow->routes = collect($routes);
    }
}
