<?php /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

namespace Pyradic\Platform;

use Anomaly\Streams\Platform\Addon\Event\AddonsHaveRegistered;
use Anomaly\Streams\Platform\Entry\Event\GatherParserData;
use Anomaly\Streams\Platform\Event\Booted;
use Anomaly\Streams\Platform\Event\Booting;
use Anomaly\Streams\Platform\Event\Ready;
use Anomaly\Streams\Platform\View\Event\TemplateDataIsLoading;
use Crvs\DepartmentsModule\Http\Middleware\EnforceUserDepartment;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Laradic\Idea\IdeaServiceProvider;
use Laradic\Support\SupportServiceProvider;
use Pyradic\IdeHelper\IdeHelperServiceProvider;
use Pyradic\Platform\Addon\Theme\Command\LoadParentTheme;
use Pyradic\Platform\Command\AddAddonOverrides;
use Pyradic\Platform\Command\AddPathOverrides;
use Pyradic\Platform\Console\AddonListCommand;
use Pyradic\Platform\Console\IdeHelperModelsCommand;
use Pyradic\Platform\Console\IdeHelperPlatformCommand;
use Pyradic\Platform\Console\IdeHelperStreamsCommand;
use Pyradic\Platform\Console\RouteListCommand;

class PlatformServiceProvider extends ServiceProvider
{
    use DispatchesJobs;

    public function register()
    {
//        $this->mergeConfigFrom(dirname(__DIR__) . '/config/crvs.platform.php', 'crvs.platform');
//        $this->mergeConfigFrom(dirname(__DIR__) . '/config/crvs.applications.php', 'crvs.applications');

        $this->app->register(SupportServiceProvider::class);
        $this->app->register(Http\HttpServiceProvider::class);
        Arr::macro('cut', function (array &$array, array $names) {
            $res   = Arr::only($array, $names);
            $array = Arr::except($array, $names);
            return $res;
        });
        $this->app->register(\EddIriarte\Console\Providers\SelectServiceProvider::class);
        if (config('app.debug')) {
            $this->app->make('events')->listen(Booted::class, function () {
//                $this->dispatchNow(new ClearAssets());
            });
        }
        $this->app->events->listen(TemplateDataIsLoading::class, function(TemplateDataIsLoading $event){
            $t = $event->getTemplate();
            return ;
        });
        $this->app->events->listen('creating:*', function($view){
            return;
        });

        $this->registerCommands();
        $this->registerMiddleware();
        $this->registerViewFinder();
        $this->registerTwigComponents();

        $this->registerAddonOverrides();
        $this->registerStreamOverrides();
        $this->registerStreamCompilerOverrides();

//        Hooks::register('crvs.module.departments::middleware.enforce', function (EnforceUserDepartment $middleware) {
//            return;
//        });
        // $this->app->register(Components\ComponentsServiceProvider::class);

    }

    protected function registerStreamCompilerOverrides()
    {

        $this->app->bind(\Anomaly\Streams\Platform\Addon\FieldType\FieldTypeParser::class, \Pyradic\Platform\Addon\FieldType\FieldTypeParser::class);

        $this->app->events->listen(GatherParserData::class, function (GatherParserData $event) {
            $data   = $event->getData();
            $stream = $event->getStream();
            $data->put('template', file_get_contents(__DIR__ . '/Entry/entry.stub'));
            $relations = $data->get('relations');

            if ($stream->getNamespace() === 'users' && $stream->getSlug() === 'users') {
                $with = str_replace('[', "['department'", $data->get('with', '[]'));
                $data->put('with', $with);
                return;
            }
            return;
        });
    }

    protected function registerCommands()
    {
        $config = $this->app->make(Repository::class);
//        $commands                                                               = $config->get('streams.commands', []);
//        $commands[ \Anomaly\Streams\Platform\Installer\Console\Install::class ] = Installer\InstallCommand::class;
//        $config->set('streams.commands', $commands);

//        $this->app->extend(\Anomaly\Streams\Platform\Installer\Console\Install::class, function ($command) {
//            return $this->app->make(Installer\InstallCommand::class);
//        });

        $this->app->register(IdeaServiceProvider::class);
        $this->app->register(IdeHelperServiceProvider::class);
        $this->app->extend('command.ide-helper.models', function () {
            return new IdeHelperModelsCommand($this->app[ 'files' ]);
        });
        $this->app->singleton('command.addon.list', function ($app) {
            return new AddonListCommand();
        });
        $this->commands([ 'command.ide-helper.models',  'command.addon.list' ]);
    }

    protected function registerMiddleware()
    {
        /** @var \Illuminate\Foundation\Http\Kernel $kernel */
        $kernel = $this->app->make(Kernel::class);
        $kernel->prependMiddleware(Http\Middleware\DebugLoginMiddleware::class);
        if ($this->app[ 'config' ][ 'webpack.middleware.enabled' ]) {
            $kernel->prependMiddleware($this->app[ 'config' ]->get('webpack.middleware.class', Http\Middleware\WebpackHotMiddleware::class));
        }
    }

    protected function registerViewFinder()
    {
        /** @var FileViewFinder $oldViewFinder */
        $oldViewFinder = $this->app[ 'view.finder' ];

        $this->app->bind('view.finder', function ($app) use ($oldViewFinder) {
            $paths      = array_merge(
                $app[ 'config' ][ 'view.paths' ],
                $oldViewFinder->getPaths()
            );
            $viewFinder = new FileViewFinder($app[ 'files' ], $paths, $oldViewFinder->getExtensions());

            foreach ($oldViewFinder->getHints() as $namespace => $hints) {
                $viewFinder->addNamespace($namespace, $hints);
            }
            return $viewFinder;
        });

        $this->app[ 'view' ]->setFinder($this->app[ 'view.finder' ]);
    }

    public function registerTwigComponents()
    {
//        $this->app->extend('twig.options', function($options){
//            $options['base_template_class'] = Template::class;
//            return $options;
//        });
        $this->app->extend('view', function (Factory $factory) {
            /** @var \Illuminate\View\FileViewFinder $finder */
            $finder = $factory->getFinder();
            $finder->addNamespace('components', []);
        });
    }

    protected function registerAddonOverrides()
    {
        resolve('events')->listen(Ready::class, function (Ready $event) {
            $this->dispatchNow(new AddPathOverrides(path_join(__DIR__, '..', 'resources')));

            $active = resolve(\Anomaly\Streams\Platform\Addon\Theme\ThemeCollection::class)->active();
            $this->dispatchNow(new AddAddonOverrides($active));
        });

        resolve('events')->listen(AddonsHaveRegistered::class, function (AddonsHaveRegistered $event) {
            foreach ($event->getAddons()->installed()->enabled() as $addon) {
                $this->dispatchNow(new AddAddonOverrides($addon));
            }
        });
    }

    public function registerStreamOverrides()
    {

        $events = $this->app->make(Dispatcher::class);
        $events->listen(Ready::class, function (Ready $event) {
            $this->dispatchNow(new LoadParentTheme());
        });
        $events->listen(Booting::class, function (Booting $event) {
            $bindings   = [ 'Anomaly\Streams\Platform\Addon\Theme\Command\LoadCurrentTheme' => Addon\Theme\Command\LoadParentTheme::class, ];
            $singletons = [
                'Anomaly\Streams\Platform\Asset\Asset'           => Asset\Asset::class,
//        'Anomaly\Streams\Platform\Asset\Asset'           => 'Anomaly\Streams\Platform\Asset\Asset',
//        'Anomaly\Streams\Platform\Asset\AssetPaths'      => 'Anomaly\Streams\Platform\Asset\AssetPaths',
//        'Anomaly\Streams\Platform\Asset\AssetParser'     => 'Anomaly\Streams\Platform\Asset\AssetParser',
//        'Anomaly\Streams\Platform\Asset\AssetFilters'    => 'Anomaly\Streams\Platform\Asset\AssetFilters',
//        'Anomaly\Streams\Platform\Addon\AddonLoader'     => 'Anomaly\Streams\Platform\Addon\AddonLoader',
//        'Anomaly\Streams\Platform\Addon\AddonBinder'     => 'Anomaly\Streams\Platform\Addon\AddonBinder',
//        'Anomaly\Streams\Platform\Addon\AddonCollection' => 'Anomaly\Streams\Platform\Addon\AddonCollection',
                'Anomaly\Streams\Platform\Addon\AddonManager'    => Addon\AddonManager::class,
                'Anomaly\Streams\Platform\Addon\AddonIntegrator' => Addon\AddonIntegrator::class,
                'Anomaly\Streams\Platform\Addon\AddonProvider'   => Addon\AddonProvider::class,
            ];
            foreach ($bindings as $abstract => $concrete) {
                $this->app->bind($abstract, $concrete);
            }
            foreach ($singletons as $abstract => $concrete) {
                $this->app->singleton($abstract, $concrete);
            }
        });
    }

    public function boot()
    {

        $this->app->singleton('command.route.list', function ($app) {
            return new RouteListCommand($app[ 'router' ]);
        });
    }

}
