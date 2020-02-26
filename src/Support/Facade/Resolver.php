<?php

namespace Pyro\Platform\Support\Facade;

use Illuminate\Support\Facades\Facade;

class Resolver extends Facade
{

    protected static function getFacadeAccessor()
    {
        return \Anomaly\Streams\Platform\Support\Resolver::class;
    }
}
