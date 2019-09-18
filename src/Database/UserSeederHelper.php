<?php

namespace Pyradic\Platform\Database;

use Anomaly\UsersModule\User\UserActivator;
use Laradic\Support\Concerns\DispatchesJobs;
use Anomaly\Streams\Platform\Traits\FiresCallbacks;
use Anomaly\UsersModule\Role\Contract\RoleInterface;
use Anomaly\UsersModule\User\Contract\UserInterface;
use Anomaly\UsersModule\Role\Contract\RoleRepositoryInterface;
use Anomaly\UsersModule\User\Contract\UserRepositoryInterface;

class UserSeederHelper
{
    use DispatchesJobs;
    use FiresCallbacks;

    public function createUser($username, $email, $password, $roleSlugs = [ 'user' ])
    {
        $users          = resolve(UserRepositoryInterface::class);
        $roleRepository = resolve(RoleRepositoryInterface::class);
        $activator      = resolve(UserActivator::class);
        $roles          = $roleRepository->all()->filter(function (RoleInterface $role) use ($roleSlugs) {
            return in_array($role->getSlug(), $roleSlugs, true);
        });

        $users->unguard();

        $user = $users->findByUsername($username);

        if ($user instanceof UserInterface) {
            return $user;
        }

        /* @var UserInterface $user */
        $user = $users->create([
            'display_name' => $username,
            'email'        => $email,
            'username'     => $username,
            'password'     => $password,
        ]);

        $this->fire('created_user', [ $user, $roles ]);

        $user->roles()->sync($roles->ids());
        $activator->force($user);

        return $user;
    }
}
