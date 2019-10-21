<?php


return [
    'cp_scripts' => [
        'enabled'   => true,
        'bootstrap' => true,
    ],
    'webpack'    => [
        'active'  => false,
        'enabled' => env('WEBPACK_ENABLED', false),
        'path'    => env('WEBPACK_PATH', 'storage/webpack.json'),
    ],
];
