<?php

namespace Pyro\Platform\Facades;

use Illuminate\Support\Facades\Facade;

class Platform extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Pyro\Platform\Platform::class;
    }

}
