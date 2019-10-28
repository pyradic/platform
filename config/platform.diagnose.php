<?php

use Pyro\Platform\Diagnose\Checks\AppKeyIsSet;
use Pyro\Platform\Diagnose\Checks\ComposerWithDevDependenciesIsUpToDate;
use Pyro\Platform\Diagnose\Checks\ComposerWithoutDevDependenciesIsUpToDate;
use Pyro\Platform\Diagnose\Checks\ConfigurationIsCached;
use Pyro\Platform\Diagnose\Checks\ConfigurationIsNotCached;
use Pyro\Platform\Diagnose\Checks\CorrectPhpVersionIsInstalled;
use Pyro\Platform\Diagnose\Checks\DatabaseCanBeAccessed;
use Pyro\Platform\Diagnose\Checks\DebugModeIsNotEnabled;
use Pyro\Platform\Diagnose\Checks\DirectoriesHaveCorrectPermissions;
use Pyro\Platform\Diagnose\Checks\EnvFileExists;
use Pyro\Platform\Diagnose\Checks\ExampleEnvironmentVariablesAreSet;
use Pyro\Platform\Diagnose\Checks\ExampleEnvironmentVariablesAreUpToDate;
use Pyro\Platform\Diagnose\Checks\LocalesAreInstalled;
use Pyro\Platform\Diagnose\Checks\MaintenanceModeNotEnabled;
use Pyro\Platform\Diagnose\Checks\MigrationsAreUpToDate;
use Pyro\Platform\Diagnose\Checks\PhpExtensionsAreDisabled;
use Pyro\Platform\Diagnose\Checks\PhpExtensionsAreInstalled;
use Pyro\Platform\Diagnose\Checks\RoutesAreCached;
use Pyro\Platform\Diagnose\Checks\RoutesAreNotCached;
use Pyro\Platform\Diagnose\Checks\StorageDirectoryIsLinked;

return [

    /*
     * A list of environment aliases mapped to the actual environment configuration.
     */
    'environment_aliases' => [
        'prod' => 'production',
        'live' => 'production',
        'local' => 'development',
    ],

    /*
     * Common checks that will be performed on all environments.
     */
    'checks' => [
        AppKeyIsSet::class,
        CorrectPhpVersionIsInstalled::class,
        DatabaseCanBeAccessed::class             => [
            'default_connection' => true,
            'connections' => [],
        ],
        DirectoriesHaveCorrectPermissions::class => [
            'directories' => [
                storage_path(),
                base_path('bootstrap/cache'),
            ],
        ],
        EnvFileExists::class,
        ExampleEnvironmentVariablesAreSet::class,
        LocalesAreInstalled::class               => [
            'required_locales' => [
                'en_US',
                PHP_OS === 'Darwin' ? 'en_US.UTF-8' : 'en_US.utf8',
            ],
        ],
        MaintenanceModeNotEnabled::class,
        MigrationsAreUpToDate::class,
        PhpExtensionsAreInstalled::class         => [
            'extensions' => [
                'openssl',
                'PDO',
                'mbstring',
                'tokenizer',
                'xml',
                'ctype',
                'json',
            ],
            'include_composer_extensions' => true,
        ],
        //\BeyondCode\SelfDiagnosis\Checks\RedisCanBeAccessed::class => [
        //    'default_connection' => true,
        //    'connections' => [],
        //],
        StorageDirectoryIsLinked::class,
    ],

    /*
     * Environment specific checks that will only be performed for the corresponding environment.
     */
    'environment_checks' => [
        'development' => [
            ComposerWithDevDependenciesIsUpToDate::class,
            ConfigurationIsNotCached::class,
            RoutesAreNotCached::class,
            ExampleEnvironmentVariablesAreUpToDate::class,
        ],
        'production' => [
            ComposerWithoutDevDependenciesIsUpToDate::class,
            ConfigurationIsCached::class,
            DebugModeIsNotEnabled::class,
            PhpExtensionsAreDisabled::class => [
                'extensions' => [
                    'xdebug',
                ],
            ],
            RoutesAreCached::class,
            //\BeyondCode\SelfDiagnosis\Checks\ServersArePingable::class => [
            //    'servers' => [
            //        'www.google.com',
            //        ['host' => 'www.google.com', 'port' => 8080],
            //        '8.8.8.8',
            //        ['host' => '8.8.8.8', 'port' => 8080, 'timeout' => 5],
            //    ],
            //],
            //\BeyondCode\SelfDiagnosis\Checks\SupervisorProgramsAreRunning::class => [
            //    'programs' => [
            //        'horizon',
            //    ],
            //    'restarted_within' => 300,
            //],
            //\BeyondCode\SelfDiagnosis\Checks\HorizonIsRunning::class,
        ],
    ],

];
