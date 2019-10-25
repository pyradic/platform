<?php

namespace Pyro\Platform\Database;

use Anomaly\Streams\Platform\Traits\FiresCallbacks;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Traits\Macroable;

abstract class SeederHelper
{
    use DispatchesJobs;
    use WithFaker;
    use FiresCallbacks;
    use Macroable;

    public static function when($trigger, $callback)
    {
        $trigger = static::class . '::' . $trigger;

        if ( ! isset(self::$listeners[ $trigger ])) {
            self::$listeners[ $trigger ] = [];
        }

        self::$listeners[ $trigger ][] = $callback;
    }

    public function locale()
    {
        return config('streams::locales.default', app()->getLocale());
    }
}
