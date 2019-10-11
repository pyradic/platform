<?php

namespace Pyro\Platform\Console;

use Laradic\Support\Console\CommandsVisibility;
use Laradic\Support\Console\ConfiguresCommandVisibility;

class Kernel extends \Anomaly\Streams\Platform\Console\Kernel
{
    use ConfiguresCommandVisibility;

    protected function getArtisan()
    {
        return $this->addCommandVisibility(parent::getArtisan());
    }


    protected function configureVisibility(CommandsVisibility $visibility)
    {
        $visibility->hide(...[
            'app:*',
            'auth:*',
            'cache:*',
            'config:*',
            'db:*',
            'event:*',
            'key:*',
            'make:*',
            'migrate:*',
            'notifi*',
            'optimize:*',
            'package:*',
            'queue:*',
            'route:*',
            'schedule:*',
            'session:*',
            'storage:*',
            'vendor:*',
            'view:*',
        ]);
    }
}
