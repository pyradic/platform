<?php

namespace Pyro\Platform\Http;

use Laradic\Support\MultiBench;

class Kernel extends \Anomaly\Streams\Platform\Http\Kernel
{

    /**
     * Send the given request through the middleware / router.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function sendRequestThroughRouter($request)
    {
        MultiBench::on('lifecycle')->mark('sendRequestThroughRouter');
        $response = parent::sendRequestThroughRouter($request);
        MultiBench::on('lifecycle')->mark('sendRequestThroughRouter:end');
        return $response;
    }

    /**
     * Get the route dispatcher callback.
     *
     * @return \Closure
     */
    protected function dispatchToRouter()
    {
        return function ($request) {
            $this->app->instance('request', $request);
            MultiBench::on('lifecycle')->mark('dispatchToRouter');
            $response = $this->router->dispatch($request);
            MultiBench::on('lifecycle')->mark('dispatchToRouter:end');
            return $response;
        };
    }
}
