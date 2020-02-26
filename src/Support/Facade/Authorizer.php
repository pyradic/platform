<?php

namespace Pyro\Platform\Support\Facade;

use Illuminate\Support\Facades\Facade;

class Authorizer extends Facade
{

    protected static function getFacadeAccessor()
    {
        return \Anomaly\Streams\Platform\Support\Authorizer::class;
    }
}
/*

        'Anomaly\Streams\Platform\Support\Evaluator'                                         => 'Anomaly\Streams\Platform\Support\Evaluator',
        'Anomaly\Streams\Platform\Support\Currency'                                          => 'Anomaly\Streams\Platform\Support\Currency',
        'Anomaly\Streams\Platform\Support\Parser'                                            => 'Anomaly\Streams\Platform\Support\Parser',
        'Anomaly\Streams\Platform\Support\Hydrator'                                          => 'Anomaly\Streams\Platform\Support\Hydrator',
        'Anomaly\Streams\Platform\Support\Resolver'                                          => 'Anomaly\Streams\Platform\Support\Resolver',
        'Anomaly\Streams\Platform\Support\Translator'                                        => 'Anomaly\Streams\Platform\Support\Translator',
 */
