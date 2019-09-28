<?php

namespace Pyradic\Platform\Http;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;

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
