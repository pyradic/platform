<?php /** @noinspection PhpFullyQualifiedNameUsageInspection */

/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

namespace Pyro\Platform;

use Anomaly\Streams\Platform\Entry\Event\GatherParserData;
use Anomaly\Streams\Platform\View\ViewIncludes;
use Anomaly\UsersModule\User\Login\LoginFormBuilder;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\ServiceProvider;
use Pyro\Platform\User\Permission\PermissionSetCollection;

class PlatformServiceProvider extends ServiceProvider
{
    use DispatchesJobs;

    protected $providers = [
        \EddIriarte\Console\Providers\SelectServiceProvider::class,
        \Laradic\Support\SupportServiceProvider::class,
        \Pyro\Platform\Asset\AssetServiceProvider::class,
        \Pyro\Platform\Bus\BusServiceProvider::class,
        \Pyro\Platform\Console\ConsoleServiceProvider::class,
        \Pyro\Platform\Database\DatabaseServiceProvider::class,
        \Pyro\Platform\Http\HttpServiceProvider::class,
        \Pyro\Platform\View\ViewServiceProvider::class,
        \Pyro\Platform\Webpack\WebpackServiceProvider::class,
    ];

    protected $devProviders = [
        \Pyro\IdeHelper\IdeHelperServiceProvider::class,
    ];

    public function boot(\Anomaly\Streams\Platform\Asset\Asset $assets, ViewIncludes $includes)
    {
//        $this->registerCommands();

        if ($this->app->config[ 'platform.cp_scripts.enabled' ]) {
            $includes->include('cp_scripts', 'platform::cp_scripts');
        }
        $assets->addPath('node_modules', base_path('node_modules'));
        $assets->addPath('platform', dirname(__DIR__) . '/resources');
        $this->app->view->share('platform', $this->app->platform);
    }

    public function register()
    {
        $this->mergeConfigs();
        $this->registerProviders();
        if ($this->app->environment('local')) {
            $this->registerDevProviders();
            $this->registerDevLoginForm();
            $this->registerDebugLogin();
        }
        $this->registerPlatform();
        $this->registerPermissionSets();
//        $this->registerSeeders();
        $this->registerEntryModelGeneratorStub();
//        $this->registerAddonPaths();
//        $this->registerAssetOverride();
//        $this->registerThemeInheritance();
//        $this->registerCommands();
//        $this->registerViewFinder();
//        $this->registerDotenvEditor();
//        $this->registerEnvSetCommand();
    }

    protected function mergeConfigs()
    {
        $this->mergeConfigFrom(dirname(__DIR__) . '/config/platform.php', 'platform');
        $this->mergeConfigFrom(dirname(__DIR__) . '/config/platform.permission_sets.php', 'platform.permission_sets');
    }

    protected function registerPermissionSets()
    {
        $this->app->singleton('permission_set_collection', function ($app) {
            $sets = new PermissionSetCollection();
            $data = $this->app->config->get('platform.permission_sets', []);
            $sets->registerFromArray($data);
            return $sets;
        });
        $this->app->alias('permission_set_collection', PermissionSetCollection::class);
    }

//    protected function registerSeeders()
//    {
//        \Pyro\Platform\Database\Seeder\UserSeeder::registerSeed('users');
//    }

    protected function registerProviders()
    {
        array_walk($this->providers, [ $this->app, 'register' ]);
    }

    protected function registerDevProviders()
    {
        array_walk($this->devProviders, [ $this->app, 'register' ]);
    }

    protected function registerDevLoginForm()
    {
        $this->app->extend('login', function (LoginFormBuilder $login) {
            $login->on('built', function (LoginFormBuilder $builder) {
                $builder->getFormField('email')->setValue(env('ADMIN_EMAIL'));
                $builder->getFormField('password')->setValue(env("ADMIN_PASSWORD"));
            });
            return $login;
        });
    }

    protected function registerDebugLogin()
    {
        /** @var \Illuminate\Foundation\Http\Kernel $kernel */
        $kernel = $this->app->make(Kernel::class);
        $kernel->prependMiddleware(Http\Middleware\DebugLoginMiddleware::class);
    }

    protected function registerPlatform()
    {
        $this->app->singleton('platform', function (Application $app) {
            $platform = new Platform(
                [],
                [ 'debug' => $this->app->config[ 'app.debug' ], 'csrf' => $app->session->token() ],
                [ 'pyro.pyro__platform.PlatformServiceProvider' ]
            );
            $platform->addPublicScript('assets/js/pyro__platform.js');
            return $platform;
        });
        $this->app->alias('platform', Platform::class);
    }

    protected function registerEntryModelGeneratorStub()
    {

        // stream compile entry model template
        $this->app->events->listen(GatherParserData::class, function (GatherParserData $event) {
            $event->getData()->put('template', file_get_contents(__DIR__ . '/Entry/entry.stub'));
        });
    }

//    protected function registerAddonPaths()
//    {
//        // addon paths
//        $this->app->events->listen(Ready::class, function (Ready $event) {
//            $this->dispatchNow(new AddPathOverrides(path_join(__DIR__, '..', 'resources')));
//
//            $active = resolve(\Anomaly\Streams\Platform\Addon\Theme\ThemeCollection::class)->active();
//            $this->dispatchNow(new AddAddonOverrides($active));
//        });
//
//        $this->app->events->listen(AddonsHaveRegistered::class, function (AddonsHaveRegistered $event) {
//            foreach ($event->getAddons()->installed()->enabled() as $addon) {
//                $this->dispatchNow(new AddAddonOverrides($addon));
//            }
//        });
//    }
//    protected function registerThemeInheritance()
//    {
//        $this->app->events->listen(Ready::class, function (Ready $event) {
//            $this->dispatchNow(new LoadParentTheme());
//        });
//    }
//
//    protected function registerAssetOverride()
//    {
//        $this->app->events->listen(Booting::class, function (Booting $event) {
//            $this->app->singleton('Anomaly\Streams\Platform\Asset\Asset', Asset::class);
//        });
//    }
//
//    protected function registerCommands()
//    {
//        $this->app->singleton('command.addon.list', function ($app) {
//            return new AddonListCommand();
//        });
//        $this->app->singleton('command.platform.seed', function ($app) {
//            return new SeedCommand();
//        });
//        $this->app->singleton('command.route.list', function ($app) {
//            return new RouteListCommand($app[ 'router' ]);
//        });
//        $this->app->singleton('command.database.truncate', function ($app) {
//            return new DatabaseTruncateCommand();
//        });
//        $this->app->singleton('command.platform.permissions', function ($app) {
//            return new PermissionsCommand();
//        });
//        $this->commands([ 'command.platform.seed', 'command.addon.list', 'command.database.truncate', 'command.platform.permissions' ]);
//    }
//
//    protected function registerViewFinder()
//    {
//        /** @var FileViewFinder $oldViewFinder */
//        $oldViewFinder = $this->app[ 'view.finder' ];
//
//        $this->app->bind('view.finder', function ($app) use ($oldViewFinder) {
//            $paths      = array_merge(
//                $app[ 'config' ][ 'view.paths' ],
//                $oldViewFinder->getPaths()
//            );
//            $viewFinder = new FileViewFinder($app[ 'files' ], $paths, $oldViewFinder->getExtensions());
//
//            foreach ($oldViewFinder->getHints() as $namespace => $hints) {
//                $viewFinder->addNamespace($namespace, $hints);
//            }
//            $viewFinder->addNamespace('platform', dirname(__DIR__) . '/resources/views');
//            return $viewFinder;
//        });
//
//        $this->app->view->setFinder($this->app[ 'view.finder' ]);
//    }
//
//    protected function registerEnvSetCommand()
//    {
//        $this->app->extend(\Anomaly\Streams\Platform\Application\Console\EnvSet::class, function (\Anomaly\Streams\Platform\Application\Console\EnvSet $command) {
//            return $this->app->make(EnvSet::class);
//        });
//    }
//
//    protected function registerDotenvEditor()
//    {
//        $this->mergeConfigFrom(base_path('vendor/jackiedo/dotenv-editor/src/config/config.php'), 'dotenv-editor');
//        $this->app->config->set('dotenv-editor.autoBackup', false);
//        $this->app->bind('dotenv-editor', 'Jackiedo\DotenvEditor\DotenvEditor');
//    }

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
