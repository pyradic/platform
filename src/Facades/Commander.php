<?php

namespace Pyro\Platform\Facades;

use Illuminate\Support\Facades\Facade;

class Commander extends Facade
{

    protected static function getFacadeAccessor()
    {
        return \Pyro\Platform\Commander::class;
    }
}
