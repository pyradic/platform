<?php

namespace Pyro\Platform\Livewire;

use Anomaly\Streams\Platform\Addon\Addon;
use Anomaly\Streams\Platform\Addon\AddonServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Str;
use Livewire\LivewireManager;
use Pyro\Platform\Addon\AddonProvider;

class LivewireServiceProvider extends \Livewire\LivewireServiceProvider
{

    public function register()
    {
        $this->registerProvider();
        $this->registerComponentsFinder();
        $this->addAddonProviderComponentRegistration();
    }

    public function registerProvider()
    {
        $this->app->register(\Livewire\LivewireServiceProvider::class);
        $this->app->singleton(LivewireManager::class,LivewireManager::class);
        $this->app->alias(LivewireManager::class, 'livewire');
    }

    public function addAddonProviderComponentRegistration()
    {
        AddonProvider::when('registered', function (AddonServiceProvider $provider, Addon $addon) {
            foreach ($provider->getComponents() as $alias => $class) {
//                if(!class_exists($class)) continue;
                if (Str::startsWith($alias, [ 'module::', 'addon::' ])) {
                    $alias = str_replace([ 'module::', 'addon::' ], [ $addon->getNamespace() . '::', $addon->getNamespace() . '::' ], $alias);
                }
                $this->app->livewire->component($alias, $class);
            }
        });
    }

    public function registerComponentsFinder()
    {

        $this->app->singleton(\Livewire\LivewireComponentsFinder::class, function (Application $app) {
            /** @noinspection SuspiciousBinaryOperationInspection */
            $isHostedOnVapor = $_ENV[ 'SERVER_SOFTWARE' ] ?? null === 'vapor';

            $finder = new \Pyro\Platform\Livewire\LivewireComponentsFinder(
                $app,
                config('livewire.manifest_path') ?? (
                $isHostedOnVapor
                    ? '/tmp/storage/bootstrap/cache/livewire-components.php'
                    : app()->bootstrapPath('cache/livewire-components.php')),
                \Livewire\Commands\ComponentParser::generatePathFromNamespace(config('livewire.class_namespace', 'App\\Http\\Livewire'))
            );
            foreach ($app[ 'config' ]->get('platform.livewire.classes', []) as $class) {
                $finder->addClass($class);
            }
            return $finder;
        });
    }

}
