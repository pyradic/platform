<?php

return [
    'associate'         => [
        'desc'     => 'Associate departments to streams',
        'commands' => [
            [ 'departments:association', [ 'action' => 'add', 'args' => [ 'clients::clients', 'mylink', 'multi' ] ] ],
            [ 'departments:association', [ 'action' => 'add', 'args' => [ 'clients::groups', 'mylink', 'single' ] ] ],
            [ 'departments:association', [ 'action' => 'list' ] ],
        ],
    ],
    'dissociate'        => [
        'desc'     => 'Dissociate departments from streams',
        'commands' => [
            [ 'departments:association', [ 'action' => 'remove', 'args' => [ 'clients::clients', 'mylink', 'multi' ] ] ],
            [ 'departments:association', [ 'action' => 'remove', 'args' => [ 'clients::groups' ] ] ],
            [ 'departments:association', [ 'action' => 'list' ] ],
        ],
    ],
    'clients:uninstall' => [
        'desc'     => 'Remove clients',
        'commands' => [
            [ 'addon:uninstall', [ 'addon' => 'clients_default_role_type' ] ],
            [ 'addon:uninstall', [ 'addon' => 'clients_requester_role_type' ] ],
            [ 'addon:uninstall', [ 'addon' => 'clients_volunteer_role_type' ] ],
            [ 'addon:uninstall', [ 'addon' => 'clients_care_role_type' ] ],
            [ 'addon:uninstall', [ 'addon' => 'clients_caretaker_role_type' ] ],
            [ 'addon:uninstall', [ 'addon' => 'clients_courses_role_type' ] ],
            [ 'addon:uninstall', [ 'addon' => 'clients' ] ],
        ],
    ],
    'clients:install'   => [
        'desc'     => 'Remove clients',
        'commands' => [
            [ 'addon:install', [ 'addon' => 'clients_default_role_type' ] ],
            [ 'addon:install', [ 'addon' => 'clients_requester_role_type' ] ],
            [ 'addon:install', [ 'addon' => 'clients_volunteer_role_type' ] ],
            [ 'addon:install', [ 'addon' => 'clients_care_role_type' ] ],
            [ 'addon:install', [ 'addon' => 'clients_caretaker_role_type' ] ],
            [ 'addon:install', [ 'addon' => 'clients_courses_role_type' ] ],
            [ 'addon:install', [ 'addon' => 'clients' ] ],
            [ 'departments:association', [ 'action' => 'add', 'args' => [ 'clients::clients', 'mylink', 'multi' ] ] ],
            [ 'departments:association', [ 'action' => 'add', 'args' => [ 'clients::groups', 'mylink', 'single' ] ] ],
            [ 'seed', [ 'names' => 'clients' ] ],
        ],
    ],
    'clients:reinstall' => [
        'desc'     => '',
        'commands' => [
            [ 'macro', [ 'macro' => 'clients:uninstall' ] ],
            [ 'macro', [ 'macro' => 'clients:install' ] ],
        ],
    ],
];

