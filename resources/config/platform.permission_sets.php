<?php


return [

    'clients.user'     => [
        'crvs.module.clients::clients.read',
        'crvs.module.clients::roles.read',
        'crvs.module.clients::clients.read',
    ],
    'clients.manager'  => [
        'crvs.module.clients::clients.*',
        'crvs.module.clients::roles.read',
        'crvs.module.clients::fields.*',
    ],
    'departments.user' => [
        'crvs.module.departments::department.read',
    ],
    'departments.manager' => [
        'crvs.module.departments::department.*',
    ],
];
