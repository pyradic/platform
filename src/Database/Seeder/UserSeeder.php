<?php

namespace Pyro\Platform\Database\Seeder;

class UserSeeder extends \Pyro\Platform\Database\Seeder
{
    public static $name = 'users';

    public static $description = 'Creates pre-defined roles and users, including the admin.';

    public function run()
    {
        /** @var UserSeederHelper $helper */
        $helper = $this->helper(UserSeederHelper::class);
        $helper->getUsers()->truncate();
        $helper->getRoles()->truncate();
        /** @var \Anomaly\UsersModule\Role\Contract\RoleInterface[]|\Anomaly\UsersModule\Role\RoleCollection|array $roles = [
         *      'manager'            => 'Manager',
         *      'district_manager'   => 'District Manager',
         *      'statething'         => 'Statusding',
         *      'employee'           => 'Medewerker',
         *      'client_manager'     => 'Client Beheer',
         *      'assignment_manager' => 'Assignment Beheer',
         *      'event_manager'      => 'Evenement Beheer',
         *      'intern'             => 'Intern',
         * ]
         */
        $roles = $helper->createRoles([
            'admin'              => 'Administrator',
            'user'               => 'User',
            'guest'              => 'Guest',
            'manager'            => 'Manager',
            'district_manager'   => 'District Manager',
            'statething'         => 'Statusding',
            'employee'           => 'Medewerker',
            'client_manager'     => 'Client Beheer',
            'assignment_manager' => 'Assignment Beheer',
            'event_manager'      => 'Evenement Beheer',
            'intern'             => 'Intern',
        ]);

        $helper->setRolePermissions('user',[
            'anomaly.module.dashboard::dashboards.read',
            'anomaly.module.preferences::preferences.write',
            'streams::control_panel.access',
        ]);

        $users[] = $robin = $helper->createUser(env('ADMIN_USERNAME'), env('ADMIN_EMAIL'), env('ADMIN_PASSWORD'), [ 'admin' ], [ 'display_name' => 'Administrator' ]);
        $users[] = $robin = $helper->createUser('demo', 'demo@test.com', 'test', [ 'user' ], [ 'display_name' => 'Demo User' ]);
        $users[] = $robin = $helper->createUser('robin', 'robin@test.com', 'test', ['user', 'manager', 'district_manager' ], [ 'display_name' => 'Robin' ]);
        $users[] = $frank = $helper->createUser('frank', 'frank@test.com', 'test', ['user', 'district_manager' ], [ 'display_name' => 'Frank' ]);
        $users[] = $martha = $helper->createUser('martha', 'martha@test.com', 'test', [ 'user','district_manager' ], [ 'display_name' => 'Martha' ]);
        $users[] = $don = $helper->createUser('don', 'don@test.com', 'test', [ 'user','statething' ]);
        $users[] = $brook = $helper->createUser('brook', 'brook@test.com', 'test', [ 'user','employee' ]);
        $users[] = $lisa = $helper->createUser('lisa', 'lisa@test.com', 'test', [ 'user','intern' ]);

//        $robin->attachRole($roles[ 'manager' ]);
//        $frank->attachRole($roles[ 'district_manager' ]);
//        $martha->attachRole($roles[ 'district_manager' ]);
//        $don->attachRole($roles[ 'statething' ]);
//        $brook->attachRole($roles[ 'employee' ]);
//        $lisa->attachRole($roles[ 'intern' ]);
//
//        $robin->attachRole($roles[ 'district_manager' ]);
//        $frank->attachRole($roles[ 'assignment_manager' ]);
//        $martha->attachRole($roles[ 'event_manager' ]);
////        $don->attachRole($roles[ 'district_manager' ]);
////        $brook->attachRole($roles[ 'employee' ]);
////        $lisa->attachRole($roles[ 'intern' ]);
////        return compact('users','roles');
        return [ $users, $roles ];
    }

}
