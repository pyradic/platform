<?php

namespace Pyro\Platform\Support\Facade;

use Illuminate\Support\Facades\Facade;

class MessageBag extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Anomaly\Streams\Platform\Message\MessageBag::class;
    }

}
