<?php

return [
    'ide'                     => [
        'desc'     => 'Generate IDE Helpers',
        'commands' => [
            [ 'ide-helper:streams', [], '-vvv' => true ],
            [ 'ide-helper:meta', [], '-vvv' => true ],
            [ 'idea:completion', [], '-vvv' => true ],
            [ 'idea:meta', [], '-vvv' => true ],
            [ 'idea:toolbox', [], '-vvv' => true ],
            [ 'lighthouse:ide-helper', [], '-vvv' => true ],
        ],
    ],
    'associate'               => [
        'desc'     => 'Associate departments to streams',
        'commands' => [
            [ 'departments:association', [ 'action' => 'add', 'args' => [ 'clients::clients', 'mylink', 'multi' ] ] ],
            [ 'departments:association', [ 'action' => 'add', 'args' => [ 'clients::groups', 'mylink', 'single' ] ] ],
            [ 'departments:association', [ 'action' => 'list' ] ],
        ],
    ],
    'dissociate'              => [
        'desc'     => 'Dissociate departments from streams',
        'commands' => [
            [ 'departments:association', [ 'action' => 'remove', 'args' => [ 'clients::clients', 'mylink', 'multi' ] ] ],
            [ 'departments:association', [ 'action' => 'remove', 'args' => [ 'clients::groups' ] ] ],
            [ 'departments:association', [ 'action' => 'list' ] ],
        ],
    ],
    'clients:roles:uninstall' => [
        'desc'     => 'Remove clients',
        'commands' => [
            [ 'addon:uninstall', [ 'addon' => 'default_role_type' ] ],
            [ 'addon:uninstall', [ 'addon' => 'requester_role_type' ] ],
            [ 'addon:uninstall', [ 'addon' => 'volunteer_role_type' ] ],
            [ 'addon:uninstall', [ 'addon' => 'care_role_type' ] ],
            [ 'addon:uninstall', [ 'addon' => 'caretaker_role_type' ] ],
            [ 'addon:uninstall', [ 'addon' => 'courses_role_type' ] ],
        ],
    ],
    'clients:roles:install'   => [
        'desc'     => 'Install clients',
        'commands' => [
            [ 'addon:install', [ 'addon' => 'default_role_type' ] ],
            [ 'addon:install', [ 'addon' => 'requester_role_type' ] ],
            [ 'addon:install', [ 'addon' => 'volunteer_role_type' ] ],
            [ 'addon:install', [ 'addon' => 'care_role_type' ] ],
            [ 'addon:install', [ 'addon' => 'caretaker_role_type' ] ],
            [ 'addon:install', [ 'addon' => 'courses_role_type' ] ],
            ['stream:compile', []],
            ['addon:_register',[]],
            [ 'seed', [ 'names' => 'requester_role_type' ], '-vvv' => true ],
            [ 'seed', [ 'names' => 'volunteer_role_type' ], '-vvv' => true ],
            [ 'seed', [ 'names' => 'caretaker_role_type' ], '-vvv' => true ],
        ],
    ],
    'clients:install'         => [
        'desc'     => 'Install clients',
        'commands' => [
            [ 'addon:install', [ 'addon' => 'clients' ] ],
            [ 'seed', [ 'names' => 'clients' ], '-vvv' => true ],
            [ 'macro', [ 'macro' => 'clients:roles:install' ] ],
        ],
    ],
    'clients:uninstall'       => [
        'desc'     => 'Reinstall clients',
        'commands' => [
            [ 'macro', [ 'macro' => 'clients:roles:uninstall' ] ],
            [ 'addon:uninstall', [ 'addon' => 'clients' ] ],
            [ 'macro', [ 'macro' => 'clients:install' ] ],
        ],
    ],
    'clients:reinstall'       => [
        'desc'     => 'Reinstall clients',
        'commands' => [
//            [ 'departments:set-user', [ 'department' => 'mylink', '--no-interaction' => true ] ],

            [ 'addon:install', [ 'addon' => 'clients' ], '--no-interaction' => true, '-vvv' => true, '--force' => true ],
            [ 'seed', [ 'names' => 'clients' ], '--no-interaction' => true, '-vvv' => true, '--force' => true ],
            [ 'addon:install', [ 'addon' => 'default_role_type' ], '--no-interaction' => true, '-vvv' => true, '--force' => true ],
            [ 'addon:install', [ 'addon' => 'requester_role_type' ], '--no-interaction' => true, '-vvv' => true, '--force' => true ],
            [ 'addon:install', [ 'addon' => 'volunteer_role_type' ], '--no-interaction' => true, '-vvv' => true, '--force' => true ],
            [ 'addon:install', [ 'addon' => 'care_role_type' ], '--no-interaction' => true, '-vvv' => true, '--force' => true ],
            [ 'addon:install', [ 'addon' => 'caretaker_role_type' ], '--no-interaction' => true, '-vvv' => true, '--force' => true ],
            [ 'addon:install', [ 'addon' => 'courses_role_type' ], '--no-interaction' => true, '-vvv' => true, '--force' => true ],
            [ 'seed', [ 'names' => 'requester_role_type' ], '--no-interaction' => true, '-vvv' => true, '--force' => true ],
            [ 'addon:install', [ 'addon' => 'client_registrations' ], '--no-interaction' => true, '-vvv' => true, '--force' => true ],
            [ 'seed', [ 'names' => 'client_registrations' ], '--no-interaction' => true, '-vvv' => true, '--force' => true ],
        ],
    ],
    'help_requests:reinstall' => [
        'desc'     => 'Reinstall Help Requests',
        'commands' => [
            [ 'addon:reinstall', [ 'addon' => 'help_requests' ] ],
            [ 'seed', [ 'names' => 'help_requests' ] ],
        ],
    ],
    'requester:reinstall'     => [
        'desc'     => 'Reinstall Clients Requester Role Type',
        'commands' => [
            [ 'addon:reinstall', [ 'addon' => 'clients_requester_role_type' ] ],
            [ 'seed', [ 'names' => 'requester_role_type' ], '-vvv' => true ],
        ],
    ],
];

