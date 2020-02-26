<?php

namespace Pyro\Platform\Support\Facade;

use Illuminate\Support\Facades\Facade;

class Translator extends Facade
{

    protected static function getFacadeAccessor()
    {
        return \Anomaly\Streams\Platform\Support\Translator::class;
    }
}
