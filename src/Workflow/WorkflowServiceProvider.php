<?php

namespace Pyro\Platform\Workflow;

use Illuminate\Support\ServiceProvider;

class WorkflowServiceProvider extends ServiceProvider
{
    public $providers = [];

    public $bindings = [
        WorkflowBuilder::class => WorkflowBuilder::class
    ];

    public $singletons = [
        WorkflowManager::class=>WorkflowManager::class
    ];

    public function boot()
    {
//        $this->publishes([ dirname(__DIR__) . '/config/foo.php' => config_path('foo.php') ]);
    }

    public function register()
    {
//        $this->mergeConfigFrom(dirname(__DIR__) . '/config/foo.php', 'foo');
    }
}
