<?php

namespace Pyro\Platform\Support\Facade;

use Illuminate\Support\Facades\Facade;

class Configurator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Anomaly\Streams\Platform\Support\Configurator::class;
    }
}
