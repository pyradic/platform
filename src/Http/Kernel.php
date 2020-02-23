<?php

namespace Pyro\Platform\Http;

use BeyondCode\ServerTiming\Middleware\ServerTimingMiddleware;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;

class Kernel extends \Anomaly\Streams\Platform\Http\Kernel
{
    public function __construct(Application $app, Router $router)
    {
        parent::__construct($app, $router);
        $this->middleware = array_merge([
            ServerTimingMiddleware::class,
        ], $this->middleware);
    }
}
