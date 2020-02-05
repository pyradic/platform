<?php

namespace Pyro\Platform\Listener;

use Anomaly\Streams\Platform\Addon\Event\AddonWasRegistered;

class RegisterAddonSeeders
{
    public function handle(AddonWasRegistered $event)
    {
        $addon     = $event->getAddon();
        $prefix    = last(explode('\\', get_class($addon)));
        $className = $addon->getTransformedClass($prefix . 'Seeder');
        if (class_exists($className) && method_exists($className, 'registerSeed')) {
            $className::registerSeed();
        }
    }
}
