<?php /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

namespace Pyro\Platform;

use Anomaly\Streams\Platform\Addon\Event\AddonsHaveRegistered;
use Anomaly\Streams\Platform\Entry\Event\GatherParserData;
use Anomaly\Streams\Platform\Event\Booting;
use Anomaly\Streams\Platform\Event\Ready;
use Anomaly\Streams\Platform\View\Event\TemplateDataIsLoading;
use Anomaly\Streams\Platform\View\ViewIncludes;
use Anomaly\UsersModule\User\Login\LoginFormBuilder;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\ServiceProvider;
use Pyro\Platform\Addon\Theme\Command\LoadParentTheme;
use Pyro\Platform\Asset\Asset;
use Pyro\Platform\Command\AddAddonOverrides;
use Pyro\Platform\Command\AddPathOverrides;
use Pyro\Platform\Console\AddonListCommand;
use Pyro\Platform\Console\RouteListCommand;

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
        \Pyro\IdeHelper\IdeHelperServiceProvider::class,
    ];

    public function boot(\Anomaly\Streams\Platform\Asset\Asset $assets, ViewIncludes $includes, LoginFormBuilder $loginFormBuilder)
    {
        $this->registerCommands();

        if ($this->app->config[ 'platform.cp_scripts' ]) {
            $includes->include('cp_scripts', 'platform::cp_scripts');
        }
        $assets->addPath('node_modules', base_path('node_modules'));
        $assets->addPath('platform', dirname(__DIR__) . '/resources');
        $this->app->view->share('platform', $this->app->platform);
    }

    public function register()
    {
        $this->mergeConfigFrom(dirname(__DIR__) . '/config/webpack.php', 'webpack');
        array_walk($this->providers, [ $this->app, 'register' ]);
        if ($this->app->environment('local')) {
            array_walk($this->devProviders, [ $this->app, 'register' ]);
            $this->registerDevLoginForm();
        }
        $this->registerPlatform();
        $this->registerMiddleware();
        $this->registerViewFinder();
        $this->registerStreamOverrides();
    }

    protected function registerDevLoginForm()
    {
        $this->app->extend('login', function (LoginFormBuilder $login) {
            $login->on('built', function (LoginFormBuilder $builder) {
                $builder->getFormField('email')->setValue(env('ADMIN_EMAIL'));
                $builder->getFormField('password')->setValue(env("ADMIN_PASSWORD"));
                return;
            });
            return $login;
        });
    }

    protected function registerPlatform()
    {
        $this->app->singleton('platform', function (Application $app) {
            return new Platform(
                [],
                [ 'debug' => $this->app->config[ 'app.debug' ], 'csrf' => $app->session->token() ],
                [ 'pyro.pyro__platform.PlatformServiceProvider' ]
            );
        });
        $this->app->alias('platform', Platform::class);
    }

    protected function registerStreamOverrides()
    {

        // stream compile entry model template
        $this->app->events->listen(GatherParserData::class, function (GatherParserData $event) {
            $event->getData()->put('template', file_get_contents(__DIR__ . '/Entry/entry.stub'));
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
        $this->app->singleton('command.addon.list', function ($app) {
            return new AddonListCommand();
        });
        $this->app->singleton('command.route.list', function ($app) {
            return new RouteListCommand($app[ 'router' ]);
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
