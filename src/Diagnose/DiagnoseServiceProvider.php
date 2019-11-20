<?php

namespace Pyro\Platform\Diagnose;

use Anomaly\Streams\Platform\Addon\AddonCollection;
use Illuminate\Support\ServiceProvider;

class DiagnoseServiceProvider extends ServiceProvider
{
    public $providers = [];

    public $bindings = [];

    public $singletons = [];

    public function boot(AddonCollection $_addons)
    {
        $addons = $_addons->withConfig('diagnose');

        return;
    }

    public function register()
    {
        $this->app->bind('command.diagnose', DiagnoseCommand::class);

        $this->commands([
            'command.diagnose',
        ]);
    }
}
