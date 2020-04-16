<?php

namespace Pyro\Platform\Support\Facade;

use Illuminate\Support\Facades\Facade;

class BladeString extends Facade
{

    protected static function getFacadeAccessor()
    {
        return \Pyro\Platform\Support\BladeString::class;
    }
}
