<?php

namespace Pyro\Platform\Command;

use Anomaly\Streams\Platform\Application\Application;
use Anomaly\Streams\Platform\Support\Configurator;

class LoadPlatformConfiguration
{
    public function handle(Configurator $configurator, Application $application)
    {
        // Load package configuration.
        $configurator->addNamespace('platform', realpath(__DIR__ . '/../../resources/config'));

        // Load application overrides.
        $configurator->addNamespaceOverrides('platform', $application->getResourcesPath('platform/config'));

        // Load system overrides.
        $configurator->addNamespaceOverrides('platform', base_path('resources/platform/config'));
    }
}
