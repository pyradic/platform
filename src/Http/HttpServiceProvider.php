<?php

namespace Pyro\Platform\Http;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Routing\Router;

class HttpServiceProvider extends RouteServiceProvider
{
    public function map(Router $router)
    {
        $router->get('/admin/_js/data.js', [
            'as' => 'streams::javascript.data',
            'uses' => 'Pyradic\Platform\Http\JavascriptController@data'
        ]);
    }
}
