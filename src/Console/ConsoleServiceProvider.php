<?php

namespace Pyro\Platform\Console;

use Illuminate\Support\ServiceProvider;
use Jackiedo\DotenvEditor\DotenvEditor;

class ConsoleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerEnvSetCommand();
    }

    public function boot()
    {
        $this->registerDotenvEditor();
        $this->registerCommands();
    }

    protected function registerEnvSetCommand()
    {
        $this->app->extend(\Anomaly\Streams\Platform\Application\Console\EnvSet::class, function (\Anomaly\Streams\Platform\Application\Console\EnvSet $command) {
            return $this->app->make(EnvSet::class);
        });
    }

    protected function registerDotenvEditor()
    {
        $this->mergeConfigFrom(base_path('vendor/jackiedo/dotenv-editor/src/config/config.php'), 'dotenv-editor');
        $this->app->config->set('dotenv-editor.autoBackup', false);
        $this->app->bind('dotenv-editor', DotenvEditor::class);
    }

    protected function registerCommands()
    {
        $this->app->singleton('command.addon.list', function ($app) {
            return new AddonListCommand();
        });
        $this->app->singleton('command.platform.seed', function ($app) {
            return new SeedCommand();
        });
        $this->app->singleton('command.route.list', function ($app) {
            return new RouteListCommand($app[ 'router' ]);
        });
        $this->app->singleton('command.database.truncate', function ($app) {
            return new DatabaseTruncateCommand();
        });
        $this->app->singleton('command.platform.permissions', function ($app) {
            return new PermissionsCommand();
        });
        $this->commands([ 'command.platform.seed', 'command.addon.list', 'command.database.truncate', 'command.platform.permissions' ]);
    }

}
