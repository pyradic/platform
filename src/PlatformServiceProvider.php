<?php /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

namespace Pyradic\Platform;

use Anomaly\Streams\Platform\Addon\Event\AddonsHaveRegistered;
use Anomaly\Streams\Platform\Entry\Event\GatherParserData;
use Anomaly\Streams\Platform\Event\Booting;
use Anomaly\Streams\Platform\Event\Ready;
use Anomaly\Streams\Platform\View\Event\TemplateDataIsLoading;
use Anomaly\Streams\Platform\View\ViewIncludes;
use Anomaly\UsersModule\User\Login\LoginFormBuilder;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Pyradic\Platform\Addon\Theme\Command\LoadParentTheme;
use Pyradic\Platform\Asset\Asset;
use Pyradic\Platform\Command\AddAddonOverrides;
use Pyradic\Platform\Command\AddPathOverrides;
use Pyradic\Platform\Console\AddonListCommand;
use Pyradic\Platform\Console\IdeHelperModelsCommand;
use Pyradic\Platform\Console\RouteListCommand;

class PlatformServiceProvider extends ServiceProvider
{
    use DispatchesJobs;

    protected $providers = [
        \EddIriarte\Console\Providers\SelectServiceProvider::class,
        \Laradic\Support\SupportServiceProvider::class,
        Http\HttpServiceProvider::class,
        Bus\BusServiceProvider::class,
    ];

    protected $devProviders = [
        \Laradic\Idea\IdeaServiceProvider::class,
        \Pyradic\IdeHelper\IdeHelperServiceProvider::class,
        \Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,
    ];

    public function boot(\Anomaly\Streams\Platform\Asset\Asset $assets, ViewIncludes $includes, LoginFormBuilder $loginFormBuilder)
    {
        $includes->include('cp_scripts', 'platform::cp_scripts');

        $assets->addPath('platform', dirname(__DIR__) . '/resources');
        $this->app->singleton('command.route.list', function ($app) {
            return new RouteListCommand($app[ 'router' ]);
        });
    }

    public function register()
    {
        array_walk($this->providers, [ $this->app, 'register' ]);
        if ($this->app->environment('local')) {
            array_walk($this->devProviders, [ $this->app, 'register' ]);


            $this->app->extend('login', function (LoginFormBuilder $login) {
                $login->on('built', function (LoginFormBuilder $builder) {
                    $builder->getFormField('email')->setValue(env('ADMIN_EMAIL'));
                    $builder->getFormField('password')->setValue(env("ADMIN_PASSWORD"));
                    return;
                });
                return $login;
            });
        }

        $this->mergeConfigFrom(dirname(__DIR__) . '/config/webpack.php', 'webpack');

        Arr::macro('cut', function (array &$array, array $names) {
            $res   = Arr::only($array, $names);
            $array = Arr::except($array, $names);
            return $res;
        });

        $this->app->singleton('platform', function ($app) {
            return new Platform(
                [],
                [ 'debug' => $this->app->config[ 'app.debug' ] ],
                [ 'pyro.platform.PlatformServiceProvider' ]
            );
        });
        $this->app->alias( 'platform',Platform::class);

        $this->registerCommands();
        $this->registerMiddleware();
        $this->registerViewFinder();
        $this->app->view->share('platform', $this->app->platform);

        $this->registerStreamOverrides();
    }


    public function registerStreamOverrides()
    {
        $this->app->bind(\Anomaly\Streams\Platform\Addon\FieldType\FieldTypeParser::class, \Pyradic\Platform\Addon\FieldType\FieldTypeParser::class);

        // stream compile entry model template
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

        // addon paths
        $this->app->events->listen(Ready::class, function (Ready $event) {
            $this->dispatchNow(new AddPathOverrides(path_join(__DIR__, '..', 'resources')));

            $active = resolve(\Anomaly\Streams\Platform\Addon\Theme\ThemeCollection::class)->active();
            $this->dispatchNow(new AddAddonOverrides($active));
        });

        $this->app->events->listen(AddonsHaveRegistered::class, function (AddonsHaveRegistered $event) {
            foreach ($event->getAddons()->installed()->enabled() as $addon) {
                $this->dispatchNow(new AddAddonOverrides($addon));
            }
        });


        // theme & assets
        $this->app->events->listen(TemplateDataIsLoading::class, function (TemplateDataIsLoading $event) {
            $t = $event->getTemplate();
//            $assets = $this->app->make('Anomaly\Streams\Platform\Asset\Asset');
//            $assets->addPath('platform', dirname(__DIR__) . '/resources');
//            $assets->add('theme.js', 'platform::js/platform.js', [ 'webpack:platform:scripts' ]);
            return;
        });

        $this->app->events->listen(Ready::class, function (Ready $event) {
            $this->dispatchNow(new LoadParentTheme());
        });
        $this->app->events->listen(Booting::class, function (Booting $event) {
            $bindings   = [ 'Anomaly\Streams\Platform\Addon\Theme\Command\LoadCurrentTheme' => Addon\Theme\Command\LoadParentTheme::class, ];
            $singletons = [
                'Anomaly\Streams\Platform\Asset\Asset' => Asset::class,
            ];
            foreach ($bindings as $abstract => $concrete) {
                $this->app->bind($abstract, $concrete);
            }
            foreach ($singletons as $abstract => $concrete) {
                $this->app->singleton($abstract, $concrete);
            }
        });
    }

    protected function registerCommands()
    {
        $this->app->extend('command.ide-helper.models', function () {
            return new IdeHelperModelsCommand($this->app[ 'files' ]);
        });
        $this->app->singleton('command.addon.list', function ($app) {
            return new AddonListCommand();
        });
        $this->commands([ 'command.ide-helper.models', 'command.addon.list' ]);
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
            $viewFinder->addNamespace('platform', dirname(__DIR__) . '/resources/views');
            return $viewFinder;
        });

        $this->app->view->setFinder($this->app[ 'view.finder' ]);
    }

}
//if (config('app.debug')) {
//    $this->app->make('events')->listen(Booted::class, function () {
////                $this->dispatchNow(new ClearAssets());
//    });
//}
//$this->app->events->listen(TemplateDataIsLoading::class, function (TemplateDataIsLoading $event) {
//    $t = $event->getTemplate();
//    return;
//});
//$this->app->events->listen('creating:*', function ($view) {
//    return;
//});
