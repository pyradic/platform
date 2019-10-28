<?php /** @noinspection PhpFullyQualifiedNameUsageInspection */

/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

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
use Illuminate\Translation\Translator;
use Jackiedo\DotenvEditor\DotenvEditor;
use Pyro\Platform\Addon\Theme\Command\LoadParentTheme;
use Pyro\Platform\Asset\Asset;
use Pyro\Platform\Bus\BusServiceProvider;
use Pyro\Platform\Command\AddAddonOverrides;
use Pyro\Platform\Command\AddPathOverrides;
use Pyro\Platform\Console\AddonListCommand;
use Pyro\Platform\Console\DatabaseTruncateCommand;
use Pyro\Platform\Console\EnvSet;
use Pyro\Platform\Console\PermissionsCommand;
use Pyro\Platform\Console\RouteListCommand;
use Pyro\Platform\Console\SeedCommand;
use Pyro\Platform\Http\Middleware\DebugLoginMiddleware;
use Pyro\Platform\User\Permission\PermissionSetCollection;
use Pyro\Platform\View\FileViewFinder;
use Pyro\Platform\Webpack\WebpackServiceProvider;

class PlatformServiceProvider extends ServiceProvider
{
    use DispatchesJobs;

    protected $providers = [
        \EddIriarte\Console\Providers\SelectServiceProvider::class,
        \Laradic\Support\SupportServiceProvider::class,
        \Pyro\CustomInstall\CustomInstallServiceProvider::class,
        \Pyro\Platform\Fixes\FixesServiceProvider::class,
        \Pyro\Platform\Diagnose\DiagnoseServiceProvider::class,
//        \Pyro\Platform\Bus\BusServiceProvider::class,
//        \Pyro\Platform\Webpack\WebpackServiceProvider::class,
    ];

    protected $devProviders = [
        \Pyro\IdeHelper\IdeHelperServiceProvider::class,
        \Laravel\Dusk\DuskServiceProvider::class,
    ];

    public function boot(\Anomaly\Streams\Platform\Asset\Asset $assets, ViewIncludes $includes)
    {
        $this->bootConsole();

        if ($this->app->config[ 'platform.cp_scripts.enabled' ]) {
            $includes->include('cp_scripts', 'platform::cp_scripts');
        }
        $assets->addPath('node_modules', base_path('node_modules'));
        $assets->addPath('platform', dirname(__DIR__) . '/resources');
        $this->app->view->share('platform', $this->app->platform);
        Translator::macro('addAddonLines', function ($namespace, $locale, $group, $lines) {
            $this->loaded[ $namespace ][ $group ][ $locale ] = $lines;
        });
        $this->app->translator->addAddonLines('crvs.module.clients', 'nl', 'field', [ 'department' => [ 'name' => 'aa' ] ]);
        $this->app->translator->addLines([ 'field.department' => [ 'name' => 'aa' ] ], 'nl', 'crvs.module.clients');
        $this->app->events->listen(TemplateDataIsLoading::class, function () {
            if($this->app->auth->guard()->check()) {
                $this->app->view->share([ 'user' => $user = $this->app->auth->guard()->user() ]);
                $userData = collect($user->toArray())
                    ->except([ 'activation_code', 'created_at', 'created_by_id', 'deleted_at', 'password', 'updated_at', 'updated_by_id' ])
                    ->toArray();
                $this->app->platform->set('user', $userData);
            }
        });
    }

    public function register()
    {
        $this->mergeConfigs();
        $this->registerProviders();
        if ($this->app->environment('local')) {
            $this->registerDevProviders();
        }
        $this->registerPlatform();
        $this->registerAddon();
        $this->registerAsset();
        $this->registerBus();
        $this->registerConsole();
        $this->registerDatabase();
        $this->registerEntry();
        $this->registerHttp();
        $this->registerUi();
        $this->registerUser();
        $this->registerView();
        $this->registerWebpack();
    }

    protected function mergeConfigs()
    {
        $this->mergeConfigFrom(dirname(__DIR__) . '/config/platform.php', 'platform');
        $this->mergeConfigFrom(dirname(__DIR__) . '/config/platform.permission_sets.php', 'platform.permission_sets');
    }

    protected function registerProviders()
    {
        array_walk($this->providers, [ $this->app, 'register' ]);
    }

    protected function registerDevProviders()
    {
        array_walk($this->devProviders, [ $this->app, 'register' ]);
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

    protected function registerAddon()
    {
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

        // theme inheritance
        $this->app->events->listen(Ready::class, function (Ready $event) {
            $this->dispatchNow(new LoadParentTheme());
        });
    }

    protected function registerAsset()
    {
        $this->app->events->listen(Booting::class, function (Booting $event) {
            $this->app->singleton('Anomaly\Streams\Platform\Asset\Asset', Asset::class);
        });
    }

    protected function registerBus()
    {
        $this->app->register(BusServiceProvider::class);
    }

    protected function registerConsole()
    {
        $this->app->extend(\Anomaly\Streams\Platform\Application\Console\EnvSet::class, function (\Anomaly\Streams\Platform\Application\Console\EnvSet $command) {
            return $this->app->make(EnvSet::class);
        });
    }

    protected function bootConsole()
    {
        $this->mergeConfigFrom(base_path('vendor/jackiedo/dotenv-editor/src/config/config.php'), 'dotenv-editor');
        $this->app->config->set('dotenv-editor.autoBackup', false);
        $this->app->bind('dotenv-editor', DotenvEditor::class);

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

    protected function registerDatabase()
    {
        \Pyro\Platform\Database\Seeder\UserSeeder::registerSeed('users');
    }

    protected function registerEntry()
    {
        // stream compile entry model template
        $this->app->events->listen(GatherParserData::class, function (GatherParserData $event) {
            $event->getData()->put('template', file_get_contents(__DIR__ . '/Entry/entry.stub'));
        });
    }

    protected function registerHttp()
    {
        /** @var \Illuminate\Foundation\Http\Kernel $kernel */
        $kernel = $this->app->make(Kernel::class);
        $kernel->prependMiddleware(DebugLoginMiddleware::class);
    }

    protected function registerUi()
    {
        // dev login form
        $this->app->extend('login', function (LoginFormBuilder $login) {
            $login->on('built', function (LoginFormBuilder $builder) {
                $builder->getFormField('email')->setValue(env('ADMIN_EMAIL'));
                $builder->getFormField('password')->setValue(env("ADMIN_PASSWORD"));
            });
            return $login;
        });
    }

    protected function registerUser()
    {
        $this->app->singleton('permission_set_collection', function ($app) {
            $sets = new PermissionSetCollection();
            $data = $this->app->config->get('platform.permission_sets', []);
            $sets->registerFromArray($data);
            return $sets;
        });
        $this->app->alias('permission_set_collection', PermissionSetCollection::class);
    }

    protected function registerView()
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

    protected function registerWebpack()
    {
        $this->app->register(WebpackServiceProvider::class);
    }

}
