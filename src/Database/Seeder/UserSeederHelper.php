<?php

namespace Pyro\Platform\Database\Seeder;

use Anomaly\UsersModule\Role\Contract\RoleInterface;
use Anomaly\UsersModule\Role\Contract\RoleRepositoryInterface;
use Anomaly\UsersModule\Role\RoleCollection;
use Anomaly\UsersModule\User\Contract\UserInterface;
use Anomaly\UsersModule\User\Contract\UserRepositoryInterface;
use Anomaly\UsersModule\User\UserActivator;
use Pyro\Platform\Database\SeederHelper;

class UserSeederHelper extends SeederHelper
{
    protected $users;

    protected $roles;

    protected $activator;

    public function __construct(UserRepositoryInterface $users, RoleRepositoryInterface $roles, UserActivator $activator)
    {
        $this->users     = $users;
        $this->roles     = $roles;
        $this->activator = $activator;
    }

    public function createUser($username, $email, $password, $roleSlugs = [ 'user' ], array $attributes = []): UserInterface
    {
        $roles = $this->roles->all()->filter(function (RoleInterface $role) use ($roleSlugs) {
            return in_array($role->getSlug(), $roleSlugs, true);
        });

        $user = $this->users->findByUsername($username);

        if (false === $user instanceof UserInterface) {
            $this->users->unguard();
            $user = $this->users->create([
                    'display_name' => $username,
                    'email'        => $email,
                    'username'     => $username,
                    'password'     => $password,
                ] + $attributes);
            $this->users->guard();
        }

        $user->roles()->sync($roles->ids());
        $this->activator->force($user);

        $this->fire('created_user', [ $user, $roles, $this ]);

        $this->users->guard();

        return $user;
    }

    /**
     * @param array $roles = [
     *                     'role_slug' => 'Role Name'
     *                     ] || ['slug','slug_2']
     *
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

    public function createRole($slug, $name = null, $description = null, $permissions = [])
    {
        $role = $this->roles->findBySlug($slug);
        if ($role instanceof RoleInterface) {
            return $role;
        }

        if ($name === null) {
            $name = $slug;
        }
        if ($description === null) {
            $description = $name;
        }

        $this->roles->unguard();

        $role = $this->roles->create([
            $this->locale() => compact('name', 'description'),
            'slug'          => $slug,
            'permissions'   => $permissions,
        ]);

        $this->fire('created_role', [ $role, $this ]);

        $this->roles->guard();

        return $role;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getActivator()
    {
        return $this->activator;
    }


}
