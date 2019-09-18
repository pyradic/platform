<?php

namespace Pyradic\Platform\Installer;

use Illuminate\Console\Command;
use Pyradic\Platform\Command\GetPlatformRc;
use Anomaly\Streams\Platform\Console\Kernel;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Anomaly\Streams\Platform\Addon\AddonManager;
use Anomaly\Streams\Platform\Addon\Module\Module;
use Anomaly\Streams\Platform\Application\Application;
use Anomaly\Streams\Platform\Addon\Extension\Extension;
use Anomaly\Streams\Platform\Installer\InstallerCollection;
use Anomaly\Streams\Platform\Addon\Module\ModuleCollection;
use Anomaly\Streams\Platform\Entry\Command\AutoloadEntryModels;
use Anomaly\Streams\Platform\Addon\Extension\ExtensionCollection;
use Anomaly\Streams\Platform\Application\Command\ReloadEnvironmentFile;
use Anomaly\Streams\Platform\Application\Command\InitializeApplication;
use Anomaly\Streams\Platform\Installer\Console\Command\LoadBaseSeeders;
use Anomaly\Streams\Platform\Installer\Console\Command\ConfigureDatabase;
use Anomaly\Streams\Platform\Installer\Console\Command\SetDatabasePrefix;
use Anomaly\Streams\Platform\Application\Command\LoadEnvironmentOverrides;
use Anomaly\Streams\Platform\Installer\Console\Command\LoadCoreInstallers;
use Anomaly\Streams\Platform\Installer\Console\Command\LoadBaseMigrations;
use Anomaly\Streams\Platform\Installer\Console\Command\LoadApplicationInstallers;

class Installer
{
    use DispatchesJobs;

    /** @var \Anomaly\Streams\Platform\Installer\InstallerCollection */
    protected $tasks;
    /** @var \Pyradic\Platform\Installer\InstallerOptions */
    protected $options;
    /** @var \Anomaly\Streams\Platform\Addon\AddonManager */
    protected $manager;
    /** @var \Pyradic\Platform\PlatformRc */
    protected $rc;

    public function __construct(InstallerCollection $tasks, InstallerOptions $options, AddonManager $manager)
    {
        $this->tasks   = $tasks;
        $this->options = $options;
        $this->manager = $manager;
        $this->rc = $this->dispatchNow(new GetPlatformRc());
    }

    public function add(InstallerTask $task)
    {
        $this->tasks->add($task);
        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getTasks()
    {
        return $this->tasks;
    }

    public function run(Command $command = null)
    {
        $this->load();
        $this->dispatchNow(new RunInstallers($this->tasks, $this->options, $command));
    }

    protected $loaded;

    protected function load()
    {
        if($this->loaded){
            return $this;
        }
        $this->dispatchNow(new ReloadEnvironmentFile());
        $this->dispatchNow(new LoadEnvironmentOverrides());
        $this->dispatchNow(new InitializeApplication());

        $this->dispatchNow(new ConfigureDatabase());
        $this->dispatchNow(new SetDatabasePrefix());

        $tasks = $this->tasks = new InstallerCollection();

        $this->dispatchNow(new LoadCoreInstallers($tasks));
        $this->dispatchNow(new LoadApplicationInstallers($tasks));

        app()->call([ $this, 'loadModuleInstallers' ]);
        app()->call([ $this, 'loadExtensionInstallers' ]);

        $tasks->add(
            new InstallerTask(
                'streams::installer.reloading_application',
                function () {

                    \Artisan::call('env:set', [ 'line' => 'INSTALLED=true' ]);

                    $this->dispatchNow(new ReloadEnvironmentFile());
                    $this->dispatchNow(new AutoloadEntryModels()); // Don't forget!

                    $this->manager->register(true); // Register all of our addons.

                    $this->dispatchNow(new AutoloadEntryModels()); // Yes, again.
                }
            )
        );

        app()->call([ $this, 'loadModuleSeeders' ]);
        app()->call([ $this, 'loadExtensionSeeders' ]);

        $this->dispatchNow(new LoadBaseMigrations($tasks));
        $this->dispatchNow(new LoadBaseSeeders($tasks));
        return $this;
    }



    /**
     * loadExtensionSeeders method
     *
     * @param \Anomaly\Streams\Platform\Addon\Extension\ExtensionCollection $extensions
     *
     * @return void
     */
    public function loadExtensionSeeders(ExtensionCollection $extensions): void
    {
        // $this->dispatchNow(new LoadExtensionSeeders($installers));
        /* @var Extension $extension */
        foreach ($extensions as $extension) {
            if ($this->rc->shouldSkipInstall($extension) || $this->rc->shouldSkipSeed($extension)) {
                continue;
            }
            $this->add(
                InstallerTask::seed(
                    trans('streams::installer.seeding', [ 'seeding' => trans($extension->getName()) ]),
                    function (Kernel $console) use ($extension) {
                        $console->call(
                            'db:seed',
                            [
                                '--addon' => $extension->getNamespace(),
                                '--force' => true,
                            ]
                        );
                    }
                )->setAddon($extension)->setCall('db:seed', [ '--addon' => $extension->getNamespace() ])
            );
        }
    }

    /**
     * loadModuleSeeders method
     *
     * @param \Anomaly\Streams\Platform\Addon\Module\ModuleCollection $modules
     *
     * @return void
     */
    public function loadModuleSeeders(ModuleCollection $modules): void
    {
        // $this->dispatchNow(new LoadModuleSeeders($installers));
        /* @var Module $module */
        foreach ($modules as $module) {
            if ($this->rc->shouldSkipInstall($module) || $this->rc->shouldSkipSeed($module)) {
                continue;
            }
            if ($module->getNamespace() === 'anomaly.module.installer') {
                continue;
            }

            $this->add(
                InstallerTask::seed(
                    trans('streams::installer.seeding', [ 'seeding' => trans($module->getName()) ]),
                    function (Kernel $console) use ($module) {
                        $console->call(
                            'db:seed',
                            [
                                '--addon' => $module->getNamespace(),
                                '--force' => true,
                            ]
                        );
                    }
                )->setAddon($module)->setCall('db:seed', [ '--addon' => $module->getNamespace() ])
            );
        }
    }

    /**
     * loadExtensionInstallers method
     *
     * @param \Anomaly\Streams\Platform\Addon\Extension\ExtensionCollection $extensions
     * @param \Anomaly\Streams\Platform\Application\Application             $application
     *
     * @return void
     */
    public function loadExtensionInstallers(ExtensionCollection $extensions, Application $application): void
    {
        //        $this->dispatchNow(new LoadExtensionInstallers($installers));
        /* @var Extension $extension */
        foreach ($extensions as $extension) {
            if ($this->rc->shouldSkipInstall($extension)) {
                continue;
            }
            $this->add(
                InstallerTask::install(
                    trans('streams::installer.installing', [ 'installing' => trans($extension->getName()) ]),
                    function (Kernel $console) use ($extension, $application) {
                        $console->call(
                            'addon:install',
                            [
                                'addon' => $extension->getNamespace(),
                                '--app' => $application->getReference(),
                            ]
                        );
                    }
                )->setAddon($extension)->setCall('addon:install', [
                    'addon' => $extension->getNamespace(),
                    '--app' => $application->getReference(),
                ])
            );
        }
    }

    /**
     * loadModuleInstallers method
     *
     * @param \Anomaly\Streams\Platform\Addon\Module\ModuleCollection $modules
     * @param \Anomaly\Streams\Platform\Application\Application       $application
     *
     * @return void
     */
    public function loadModuleInstallers(ModuleCollection $modules, Application $application): void
    {
        //        $this->dispatchNow(new LoadModuleInstallers($installers));
        /* @var Module $module */
        foreach ($modules as $module) {
            if ($this->rc->shouldSkipInstall($module)) {
                continue;
            }
            if ($module->getNamespace() === 'anomaly.module.installer') {
                continue;
            }

            $this->add(
                InstallerTask::install(
                    trans('streams::installer.installing', [ 'installing' => trans($module->getName()) ]),
                    function (Kernel $console) use ($module, $application) {
                        $console->call(
                            'addon:install',
                            [
                                'addon' => $module->getNamespace(),
                                '--app' => $application->getReference(),
                            ]
                        );
                    }
                )->setAddon($module)->setCall('addon:install', [
                    'addon' => $module->getNamespace(),
                    '--app' => $application->getReference(),
                ])
            );
        }
    }
}
