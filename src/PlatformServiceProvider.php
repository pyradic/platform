<?php /** @noinspection PhpFullyQualifiedNameUsageInspection */

/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

namespace Pyro\Platform;

use Anomaly\Streams\Platform\Addon\Addon;
use Anomaly\Streams\Platform\Addon\AddonCollection;
use Anomaly\Streams\Platform\Addon\AddonProvider;
use Anomaly\Streams\Platform\Addon\AddonServiceProvider;
use Anomaly\Streams\Platform\Addon\Event\AddonWasRegistered;
use Anomaly\Streams\Platform\Addon\Extension\Extension;
use Anomaly\Streams\Platform\Addon\Module\Module;
use Anomaly\Streams\Platform\Asset\Asset;
use Anomaly\Streams\Platform\Entry\Event\GatherParserData;
use Anomaly\Streams\Platform\Event\Booting;
use Anomaly\Streams\Platform\Event\Ready;
use Anomaly\Streams\Platform\Ui\Form\Event\FormWasBuilt;
use Anomaly\Streams\Platform\Ui\Table\Component\View\ViewRegistry;
use Anomaly\Streams\Platform\View\Event\RegisteringTwigPlugins;
use Anomaly\Streams\Platform\View\Event\TemplateDataIsLoading;
use Anomaly\Streams\Platform\View\ViewOverrides;
use Anomaly\UsersModule\User\Contract\UserRepositoryInterface;
use Anomaly\UsersModule\User\Login\LoginFormBuilder;
use Illuminate\Console\Application as Artisan;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\View\Factory as ViewFactoryContract;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Jackiedo\DotenvEditor\DotenvEditor;
use Jenssegers\Agent\Agent;
use Pyro\Platform\Addon\Theme\Command\LoadParentTheme;
use Pyro\Platform\Command\AddPlatformAssetNamespaces;
use Pyro\Platform\Command\OverrideIconRegistryIcons;
use Pyro\Platform\Console\ClearCommand;
use Pyro\Platform\Console\DatabaseExportCommand;
use Pyro\Platform\Console\DatabaseImportCommand;
use Pyro\Platform\Console\EnvSet;
use Pyro\Platform\Console\MacroCommand;
use Pyro\Platform\Console\PermissionsCommand;
use Pyro\Platform\Console\RouteListCommand;
use Pyro\Platform\Console\SeedCommand;
use Pyro\Platform\Event\PlatformWillRender;
use Pyro\Platform\Http\Middleware\DebugLoginMiddleware;
use Pyro\Platform\Listener\AddControlPanelToJS;
use Pyro\Platform\Listener\AddJavascriptData;
use Pyro\Platform\Listener\OverrideAddons;
use Pyro\Platform\Listener\RegisterAddonSeeders;
use Pyro\Platform\Listener\SetParserStub;
use Pyro\Platform\Listener\SetSafeDelimiters;
use Pyro\Platform\Listener\SharePlatform;
use Pyro\Platform\Routing\ResponseFactory;
use Pyro\Platform\Ui\UiServiceProvider;
use Pyro\Platform\User\Permission\PermissionSetCollection;

class PlatformServiceProvider extends ServiceProvider
{
    use DispatchesJobs;

    protected $plugins = [
//        LivewirePlugin::class,
    ];

    protected $listen = [
        TemplateDataIsLoading::class => [
            SharePlatform::class,
//            AddControlPanelStructure::class,
            AddControlPanelToJS::class,
        ],
        Ready::class                 => [
            OverrideAddons::class,
            LoadParentTheme::class,
        ],
        GatherParserData::class      => [
            SetParserStub::class,
        ],
        PlatformWillRender::class    => [
            AddJavascriptData::class,
        ],
        FormWasBuilt ::class         => [
            SetSafeDelimiters::class
//            AddControlPanelStructure::class
        ],
        AddonWasRegistered::class    => [
            RegisterAddonSeeders::class,
        ],
    ];

    protected $providers = [
        \EddIriarte\Console\Providers\SelectServiceProvider::class,
        \Laradic\Support\SupportServiceProvider::class,
        \Inertia\ServiceProvider::class,
//        \Tightenco\Ziggy\ZiggyServiceProvider::class,

        \Pyro\CustomInstall\CustomInstallServiceProvider::class,
        \Pyro\Webpack\WebpackServiceProvider::class,
        \Pyro\Helpers\HelpersServiceProvider::class,

//        \Pyro\Platform\Livewire\LivewireServiceProvider::class,
        \Pyro\Platform\Bus\BusServiceProvider::class,
        \Pyro\Platform\Routing\RoutingServiceProvider::class,
        \BeyondCode\ServerTiming\ServerTimingServiceProvider::class,
//        \Pyro\Platform\Diagnose\DiagnoseServiceProvider::class,
    ];

    protected $devProviders = [
    ];

    public function boot(ViewOverrides $overrides, Request $request, ViewRegistry $viewRegistry)
    {
//        $this->app->singleton(\Anomaly\Streams\Platform\Addon\AddonProvider::class, AddonProvider::class);

        $this->bootConfig();
        $this->bootConsole();
        dispatch_now(new AddPlatformAssetNamespaces());
        dispatch_now(new OverrideIconRegistryIcons());
//        $overrides->put('pyro.theme.admin::partials/assets', 'platform::assets');
//        $overrides->put('pyrocms.theme.accelerant::partials/assets', 'platform::assets');
    }

    public function register()
    {

        AliasLoader::getInstance()->alias('ServerTiming', \BeyondCode\ServerTiming\Facades\ServerTiming::class);
        $this->mergeConfig();

        // Start listening to listeners
        $dispatcher = resolve(Dispatcher::class);
        foreach ($this->listen as $event => $listeners) {
            foreach (array_unique($listeners) as $listener) {
                $dispatcher->listen($event, $listener);
            }
        }

        // Register providers
        foreach (array_merge(
                     $this->providers,
                     $this->app->environment('local') ? $this->devProviders : []
                 ) as $provider) {
            $this->app->register($provider);
        }

        // Add twig extensions
        $this->app->events->listen(RegisteringTwigPlugins::class, function (RegisteringTwigPlugins $event) {
            $twig = $event->getTwig();

            foreach ($this->plugins as $plugin) {
                if ( ! $twig->hasExtension($plugin)) {
                    $twig->addExtension($this->app->make($plugin));
                }
            }
        });

        $this->registerAddonProviderExtras();
        $this->registerMacros();
        $this->registerPlatform();
        $this->registerAsset();
        $this->registerHttp();
        $this->registerTranslator();
        $this->registerUi();
        $this->registerUser();
        $this->registerResponseFactory();
        $this->registerView();
        $this->registerStreamsSingletons();
    }

    protected function mergeConfig()
    {
        ///pyro.platform.
        $this->mergeConfigFrom(dirname(__DIR__) . '/resources/config/platform.php', 'platform');
        $this->mergeConfigFrom(dirname(__DIR__) . '/resources/config/platform.frontend.php', 'platform.frontend');
        $this->mergeConfigFrom(dirname(__DIR__) . '/resources/config/platform.macros.php', 'platform.macros');
        $this->mergeConfigFrom(dirname(__DIR__) . '/resources/config/platform.permission_sets.php', 'platform.permission_sets');
        $this->mergeConfigFrom(dirname(__DIR__) . '/resources/config/platform.icons.php', 'platform.icons');
        $this->mergeConfigFrom(dirname(__DIR__) . '/resources/config/platform.icons.sets.fa.php', 'platform.icons.sets.fa');
        $this->mergeConfigFrom(dirname(__DIR__) . '/resources/config/platform.icons.sets.mdi.php', 'platform.icons.sets.mdi');
        $this->mergeConfigFrom(dirname(__DIR__) . '/resources/config/ziggy.php', 'ziggy');
    }

    protected function bootConfig()
    {
        $this->publishes([
            dirname(__DIR__) . '/resources/config/platform.php'                 => config_path('platform.php'),
            dirname(__DIR__) . '/resources/config/platform.frontend.php'        => config_path('platform.frontend.php'),
            dirname(__DIR__) . '/resources/config/platform.macros.php'          => config_path('platform.macros.php'),
            dirname(__DIR__) . '/resources/config/platform.permission_sets.php' => config_path('platform.permission_sets.php'),
            dirname(__DIR__) . '/resources/config/platform.icons.php'           => config_path('platform.icons.php'),
            dirname(__DIR__) . '/resources/config/platform.icons.sets.fa.php'   => config_path('platform.icons.sets.mdi.php'),
            dirname(__DIR__) . '/resources/config/platform.icons.sets.mdi.php'  => config_path('platform.icons.sets.mdi.php'),
        ], [ 'config' ]);
    }

    protected function bootConsole()
    {
        /*
         * Override the (bugged) env:set with a working one
         * @see https://github.com/pyrocms/pyrocms/issues/5043
         * @todo remove this from platform and into the affected project(s)
         */
        $this->mergeConfigFrom(base_path('vendor/jackiedo/dotenv-editor/src/config/config.php'), 'dotenv-editor');
        $this->app->config->set('dotenv-editor.autoBackup', false);
        $this->app->bind('dotenv-editor', DotenvEditor::class);
        $this->app->extend(\Anomaly\Streams\Platform\Application\Console\EnvSet::class, function (\Anomaly\Streams\Platform\Application\Console\EnvSet $command) {
            return $this->app->make(EnvSet::class);
        });

        // register custom
        $commands = [
            'command.platform.seed'            => SeedCommand::class,
            'command.platform.macro'           => MacroCommand::class,
            'command.platform.permissions'     => PermissionsCommand::class,
            'command.platform.clear'           => ClearCommand::class,
            'command.platform.database.export' => DatabaseExportCommand::class,
            'command.platform.database.import' => DatabaseImportCommand::class,
        ];
        foreach ($commands as $command => $class) {
            $this->app->singleton($command, function ($app) use ($class) {
                return $app->build($class);
            });
        }
        $this->commands(array_keys($commands));

        // override
        $this->app->singleton('command.route.list', function ($app) {
            return new RouteListCommand($app[ 'router' ]);
        });

//        $this->app->singleton('command.platform.seed', function ($app) {
//            return new SeedCommand();
//        });
//        $this->app->singleton('command.platform.macro', function ($app) {
//            return new MacroCommand();
//        });
//        $this->app->singleton('command.platform.permissions', function ($app) {
//            return new PermissionsCommand();
//        });
//        $this->app->singleton('command.platform.clear', function ($app) {
//            return new ClearCommand();
//        });
//        $this->commands([
//            'command.platform.seed',
//            'command.platform.macro',
//            'command.platform.permissions',
//            'command.platform.clear',
//        ]);
        if (class_exists(\Bamarni\Symfony\Console\Autocomplete\DumpCommand::class)) {
            $this->app->singleton('command.autocomplete.dump', function () {
                return new \Bamarni\Symfony\Console\Autocomplete\DumpCommand();
            });

            Artisan::starting(function (\Illuminate\Console\Application $artisan) {
                $artisan->add($this->app[ 'command.autocomplete.dump' ]);
            });
        }
    }

    protected function registerMacros()
    {
        AddonCollection::macro('disabled', function () {
            return $this->installable()->filter(function ($addon) {
                /* @var Module|Extension $addon */
                return ! $addon->isEnabled();
            });
        });

        Request::macro('isAdmin', function () {
            return $this->segment(1) === 'admin';
        });
    }

    protected function registerAddonProviderExtras()
    {
        AddonServiceProvider::macro('setRoutes', function ($routes) {
            $this->routes = $routes;
        });
        AddonServiceProvider::macro('getRoutesNamespace', function () {
            return $this->routesNamespace ?? null;
        });
        AddonProvider::when('register', function (AddonServiceProvider $provider, Addon $addon, AddonProvider $addonProvider) {
            $routes = $provider->getRoutes();
            $ns     = $provider->getRoutesNamespace();
            foreach ($routes as $uri => &$route) {
                // convert value to array
                if (is_string($route)) {
                    $route = [ 'uses' => $route ];
                }
                // prefix 'uses' with namespace if needed
                if ($ns && array_key_exists('uses', $route)) {
                    $route[ 'uses' ] = Str::ensureLeft($route[ 'uses' ], $ns);
                }

                // pefix 'as' with addon namespace if needed
                if (array_key_exists('as', $route) && data_get($route, 'auto_prefix', true) === true) {
                    if ( ! Str::contains($route[ 'as' ], '::')) {
                        $route[ 'as' ] = $addon->getNamespace($route[ 'as' ]);
                    }
                    $needles = [ 'addon::', 'module::' ];
                    if (Str::startsWith($route[ 'as' ], $needles)) {
                        $route[ 'as' ] = str_replace($needles, $addon->getNamespace() . '::', $route[ 'as' ]);
                    }
                    if (Str::startsWith($route[ 'as' ], '::')) {
                        $route[ 'as' ] = Str::replaceFirst('::', $addon->getNamespace() . '::', $route[ 'as' ]);
                    }
                }

                $route[ 'uri' ] = $uri;

                if (isset($route[ 'breadcrumbs' ])) {
                    foreach ($route[ 'breadcrumbs' ] as $routeBreadcrumb) {
                        $this->app->route_crumbs->addFromProvider($routeBreadcrumb, $route, $addon);
                    }
                }

                // register 'breadcrumb' with RouteCrumbs
                if (isset($route[ 'breadcrumb' ])) {
                    $this->app->route_crumbs->addFromProvider($route[ 'breadcrumb' ], $route, $addon);
                }
            }
            $provider->setRoutes($routes);
        });
    }

    protected function registerPlatform()
    {
        $this->app->singleton('platform', function ($app) {
            $platform = new Platform($app, $app[ 'webpack' ], $app[ 'html' ]);
            $platform->addWebpackEntry('@pyro/platform');
            return $platform;
        });
        $this->app->alias('platform', Platform::class);
    }

    protected function registerAsset()
    {
        $this->app->events->listen(Booting::class, function (Booting $event) {
            $this->app->singleton(Asset::class, \Pyro\Platform\Asset\Asset::class);
        });
    }

    protected function registerHttp()
    {
        /*
         * Development Feature - Login as user using a request variable (header, url parameter, etc)
         * Not enabled on production. Requires app.debug to be true
         */
        $use = $this->app->config[ 'platform.debug_login' ] === true;
        if ($use === null) {
            $use = $this->app->environment('local') && $this->app->config[ 'app.debug' ];
        }
        if ($use === true) {
            $this->app->make(Kernel::class)->pushMiddleware(DebugLoginMiddleware::class);
        }
    }

    protected function registerTranslator()
    {
        static $loaded = [];
        \Illuminate\Translation\Translator::macro('addAddonLines', function ($namespace, $locale, $group, $lines) use (&$loaded) {
            $loaded[ $namespace ][ $group ][ $locale ] = $lines;
        });
        $this->app->booting(function (Application $app) {
            $translator = $app[ Translator::class ];
            $translator->addNamespace('platform', dirname(__DIR__) . '/resources/lang');
            $translator->addAddonLines('crvs.module.clients', 'nl', 'field', [ 'department' => [ 'name' => 'aa' ] ]);
            $translator->addLines([ 'field.department' => [ 'name' => 'aa' ] ], 'nl', 'crvs.module.clients');
        });
    }

    protected function registerUi()
    {
        $this->app->register(UiServiceProvider::class);
        /*
         * Development Feature - Auto-fill login for using the admin's email/password.
         * Not enabled on production. Requires app.debug to be true
         */
        if ($this->app->environment('local') && $this->app->config[ 'app.debug' ]) {
            $this->app->extend('login', function (LoginFormBuilder $login) {
                $login->on('built', function (LoginFormBuilder $builder) {
                    $email    = env('ADMIN_EMAIL');
                    $password = env("ADMIN_PASSWORD");
                    if (resolve(Agent::class)->is('Firefox')) {
                        if (resolve(UserRepositoryInterface::class)->findByEmail('admin2@test.com')) {
                            $email    = 'admin2@test.com';
                            $password = 'test';
                        }
                    }
                    $builder->getFormField('email')->setValue($email);
                    $builder->getFormField('password')->setValue($password);
                });
                return $login;
            });
        }
    }

    protected function registerResponseFactory()
    {
        $this->app->singleton(ResponseFactoryContract::class, function ($app) {
            return new ResponseFactory($app[ ViewFactoryContract::class ], $app[ 'redirect' ]);
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
        /*
         * A slight alteration in the call order to make `theme::something` work in view overrides. As the original loader 'normalizes' it a tad to soon.
         */
        $this->app->bind('twig.loader.viewfinder', function (Application $app) {
            return $app->make(View\Loader::class, [
                    'files'     => $app[ 'files' ],
                    'finder'    => $app[ 'view' ]->getFinder(),
                    'extension' => $app[ 'twig.extension' ],
                ]
            );
        });
        $this->app->extend('view.finder', function (\Illuminate\View\FileViewFinder $viewFinder) {
            $viewFinder->addNamespace('platform', dirname(__DIR__) . '/resources/views');
            return $viewFinder;
        });

//        $this->app->booting(function (Application $app) {
//            $includes = $app[ ViewIncludes::class ];
//            if ($app[ 'config' ][ 'platform.cp_scripts.enabled' ]) {
//                $includes->include('cp_scripts', 'platform::cp_scripts');
//            }
//        });
//        $oldViewFinder = $this->app[ 'view.finder' ];
//        $this->app->bind('view.finder', function ($app) use ($oldViewFinder) {
//            /** @var FileViewFinder $oldViewFinder */
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
    }

    protected function registerStreamsSingletons()
    {
        $this->app->singleton(\Anomaly\Streams\Platform\Support\Template::class, \Anomaly\Streams\Platform\Support\Template::class);
        $this->app->singleton(\Anomaly\Streams\Platform\Support\Value::class, \Anomaly\Streams\Platform\Support\Value::class);
        $this->app->singleton(\Anomaly\Streams\Platform\Support\Decorator::class, \Anomaly\Streams\Platform\Support\Decorator::class);

        $alias = AliasLoader::getInstance();
        $alias->alias('Authorizer', \Pyro\Platform\Support\Facade\Authorizer::class);
        $alias->alias('Configurator', \Pyro\Platform\Support\Facade\Configurator::class);
        $alias->alias('Decorator', \Pyro\Platform\Support\Facade\Decorator::class);
        $alias->alias('Evaluator', \Pyro\Platform\Support\Facade\Evaluator::class);
        $alias->alias('Hydrator', \Pyro\Platform\Support\Facade\Hydrator::class);
        $alias->alias('Parser', \Pyro\Platform\Support\Facade\Parser::class);
        $alias->alias('Resolver', \Pyro\Platform\Support\Facade\Resolver::class);
        $alias->alias('Template', \Pyro\Platform\Support\Facade\Template::class);
        $alias->alias('Translator', \Pyro\Platform\Support\Facade\Translator::class);
        $alias->alias('Value', \Pyro\Platform\Support\Facade\Value::class);
        $alias->alias('MessageBag', \Pyro\Platform\Support\Facade\MessageBag::class);
    }

}
