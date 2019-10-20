<?php

namespace Pyro\Platform\Database;

use Anomaly\Streams\Platform\Traits\FiresCallbacks;
use Anomaly\UsersModule\Role\Contract\RoleInterface;
use Anomaly\UsersModule\Role\Contract\RoleRepositoryInterface;
use Anomaly\UsersModule\Role\RoleCollection;
use Anomaly\UsersModule\User\Contract\UserInterface;
use Anomaly\UsersModule\User\Contract\UserRepositoryInterface;
use Anomaly\UsersModule\User\UserActivator;
use Laradic\Support\Concerns\DispatchesJobs;

class UserSeederHelper
{
    use DispatchesJobs;
    use FiresCallbacks;

    public function createUser($username, $email, $password, $roleSlugs = [ 'user' ]): UserInterface
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
            $user->roles()->sync([]);
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

    /**
     * @param array $roles = [
     *                     'role_slug' => 'Role Name'
     *                     ] || ['slug','slug_2']
     * @return RoleInterface[]|RoleCollection
     */
    public function createRoles(array $roles)
    {
        $result = new RoleCollection();
        $assoc  = count(array_filter(array_keys($roles), 'is_int')) !== count($roles);
        foreach ($roles as $key => $value) {
            $result->push($assoc ? $this->createRole($key, $value) : $this->createRole($value));
        }
        return $result->keyBy('slug');
    }

    public function createRole($slug, $name = null, $description = null)
    {
        /** @var RoleRepositoryInterface $roles */
        $roles = resolve(RoleRepositoryInterface::class);
        $role = $roles->findBySlug($slug);
        if ($role instanceof RoleInterface) {
            return $role;
        }


        if ($name === null) {
            $name = $slug;
        }
        if ($description === null) {
            $description = $name;
        }
        /** @var RoleInterface $role */
        $role = $roles->create([ 'slug' => $slug, 'en' => compact('name', 'description'), ]);

        return $role;
    }
}
