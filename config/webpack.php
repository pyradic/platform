<?php

use Pyro\Platform\Http\Middleware\WebpackHotMiddleware;

$__filePath = config_path('webpack.json');
return [
    'enabled' => file_exists($__filePath),
    'middleware' => [
        'enabled' => true,
        'class' => WebpackHotMiddleware::class
    ],
    'bundles' => file_exists($__filePath) ? json_decode(file_get_contents($__filePath), true) : [],
];
