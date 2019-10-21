<?php

namespace Pyro\Platform\Database\Seeders;

use Pyro\Platform\Database\UserSeederHelper;

class UserSeeder extends \Pyro\Platform\Database\Seeder
{
    public function run()
    {
        /** @var UserSeederHelper $helper */
        $helper = resolve(UserSeederHelper::class);
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
            'manager'            => 'Manager',
            'district_manager'   => 'District Manager',
            'statething'         => 'Statusding',
            'employee'           => 'Medewerker',
            'client_manager'     => 'Client Beheer',
            'assignment_manager' => 'Assignment Beheer',
            'event_manager'      => 'Evenement Beheer',
            'intern'             => 'Intern',
        ]);
//        $helper->createUser('robin', 'robin@test.com', 'test')->attachRole();

        $users[] = $robin = $helper->createUser('robin', 'robin@test.com', 'test');
        $users[] = $frank = $helper->createUser('frank', 'frank@test.com', 'test');
        $users[] = $martha = $helper->createUser('martha', 'martha@test.com', 'test');
        $users[] = $don = $helper->createUser('don', 'don@test.com', 'test');
        $users[] = $brook = $helper->createUser('brook', 'brook@test.com', 'test');
        $users[] = $lisa = $helper->createUser('lisa', 'lisa@test.com', 'test');

        $robin->attachRole($roles[ 'manager' ]);
        $frank->attachRole($roles[ 'district_manager' ]);
        $martha->attachRole($roles[ 'district_manager' ]);
        $don->attachRole($roles[ 'statething' ]);
        $brook->attachRole($roles[ 'employee' ]);
        $lisa->attachRole($roles[ 'intern' ]);

        $robin->attachRole($roles[ 'district_manager' ]);
        $frank->attachRole($roles[ 'assignment_manager' ]);
        $martha->attachRole($roles[ 'event_manager' ]);
//        $don->attachRole($roles[ 'district_manager' ]);
//        $brook->attachRole($roles[ 'employee' ]);
//        $lisa->attachRole($roles[ 'intern' ]);
//        return compact('users','roles');
        return [ $users, $roles ];
    }

}
