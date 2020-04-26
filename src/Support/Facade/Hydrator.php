<?php

namespace Pyro\Platform\Support\Facade;

use Illuminate\Support\Facades\Facade;

class Hydrator extends Facade
{

    protected static function getFacadeAccessor()
    {
        return \Pyro\Platform\Support\Hydrator::class;
    }
}
