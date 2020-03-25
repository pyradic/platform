<?php

namespace Pyro\Platform\Workflow;

use Anomaly\Streams\Platform\Addon\Event\AddonsHaveRegistered;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class WorkflowServiceProvider extends ServiceProvider
{
    public $bindings = [
        Workflow::class => Workflow::class,
    ];

    public $singletons = [
        WorkflowManager::class => WorkflowManager::class,
    ];

    public function register()
    {
        $this->app->events->listen(
            AddonsHaveRegistered::class,
            RegisterAddonWorkflows::class
        );

    }

    public function boot(Router $router)
    {
        $router->bind('workflow', function ($value) {
            $manager = resolve(WorkflowManager::class);
            return $manager->has($value) ? $manager->get($value) : abort(500, "Cannot find workflow [{$value}]");
        });
    }
}
