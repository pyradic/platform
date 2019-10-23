<?php

namespace Pyro\Platform\Console;

use Anomaly\Streams\Platform\Addon\Addon;
use Anomaly\Streams\Platform\Addon\AddonCollection;
use Anomaly\UsersModule\Role\Contract\RoleRepositoryInterface;
use Anomaly\UsersModule\User\Contract\UserRepositoryInterface;
use Illuminate\Console\Command;
use Pyro\Platform\User\Permission\PermissionSet;
use Pyro\Platform\User\Permission\PermissionSetCollection;
use Symfony\Component\VarDumper\VarDumper;

class PermissionsCommand extends Command
{
    protected $signature = 'permissions';

    protected $description = '';

    public function handle(PermissionSetCollection $sets, AddonCollection $addons, RoleRepositoryInterface $roleRepository, UserRepositoryInterface $userRepository)
    {
        $entryType = $this->choice('Target entry type', [ 'user', 'role' ], 'user');

        $entries = collect();

        if ($this->option('no-interaction')) {
            $entries =collect([ $userRepository->findByUsername('frank') ]);
        } elseif ($entryType === 'role') {
            $roles       = $roleRepository->all()->keyBy('slug');
            $targetRoles = $this->select('Target roles', $roles->keys()->toArray());
            $entries     = $roles->only($targetRoles);
        } elseif ($entryType === 'user') {
            $users       = $userRepository->all()->keyBy('username');
            $targetUsers = $this->select('Target users', $users->keys()->toArray());
            $entries     = $users->only($targetUsers);
        }

        $permissions = new PermissionSet();

        $by = $this->choice('Select permissions by', [ 'set', 'permission', 'addon' ], 'permission');
        if ($by === 'set') {
            $selected = $this->select('Select sets', $sets->keys()->toArray());
            $sets->only($selected);
            $permissions = $sets->only($selected)->combineToSet();
        } elseif ($by === 'permission') {
            $allPermissions = $addons->installed()->withConfig('permissions')->map(function (Addon $addon) {
                return $this->getAddonPermissions($addon);
            })->flatten();

            if ($this->option('no-interaction')) {
                $selected = ['anomaly.module.settings::settings.write', 'anomaly.module.dashboard::dashboards.write'];
            } else {
                $selected = $this->select('Permissions', $allPermissions->toArray());
            }
            $permissions->add($selected);

        } elseif($by === 'addon'){
            $addonNamespaces = $addons->installed()->withConfig('permissions')->map->getNamespace();
            $selected = $this->select('Addons', $addonNamespaces);
            foreach($selected as $addonNamespace){
                $addon = $addons->get($addonNamespace);
                $permissions->add($this->getAddonPermissions($addon));
            }
        }

        $this->info("Adding: \n - " . implode("\n - ", $permissions->toArray()));
        $this->line('');
        $this->info('To '.$entryType .'s: '. $entries->map->getKey());

        foreach($entries as $entry){

        }
    }

    protected function getAddonPermissions(Addon $addon)
    {
        $groups      = collect(config($addon->getNamespace('permissions')));
        $permissions = $groups->map(function ($permissions, $group) use ($addon) {
            $result = array_map(function ($permission) use ($group, $addon) {
                return $addon->getNamespace($group . '.' . $permission);
            }, $permissions);
            return $result;
        })->flatten();
        return $permissions;
    }

    protected function dump($name, ...$vars)
    {
        $this->line($name);
        VarDumper::dump($vars);
    }
}
