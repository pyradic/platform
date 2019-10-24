<?php

namespace Pyro\Platform\Console;

use Anomaly\Streams\Platform\Addon\Addon;
use Anomaly\Streams\Platform\Addon\AddonCollection;
use Anomaly\UsersModule\Role\Contract\RoleRepositoryInterface;
use Anomaly\UsersModule\User\Contract\UserRepositoryInterface;
use Closure;
use Illuminate\Console\Command;
use Pyro\Platform\User\Permission\PermissionSet;
use Pyro\Platform\User\Permission\PermissionSetCollection;
use Symfony\Component\VarDumper\VarDumper;

class PermissionsCommand extends Command
{
    protected $signature = 'permissions 
                                        {entryType? : <comment>user</comment> or <comment>role</comment>} 
                                        {entryIds? : <comment>username</comment> or <comment>role slug</comment>} 
                                        {by? : <comment>set</comment>, <comment>permission</comment> or <comment>addon</comment>} 
                                        {byVal? : <comment>set names</comment> or <comment>permissions</comment> or <comment>addon namespaces</comment>}';

    protected $description = '';

    public function handle(
        PermissionSetCollection $sets,
        AddonCollection $addons,
        RoleRepositoryInterface $roleRepository,
        UserRepositoryInterface $userRepository
    )
    {
        $entryType = $this->argumentElse('entryType', function () {
            return $this->choice('Target entry type', [ 'user', 'role' ], 'user');
        });

        $entries = collect();

        if ($this->option('no-interaction')) {
            $entries = collect([ $userRepository->findByUsername('frank') ]);
        } elseif ($entryType === 'role') {
            $roles       = $roleRepository->all();
            $targetRoles = $this->argumentElse('entryIds', function () use ($roles) {
                return $this->select('Target roles', $roles->pluck('slug')->toArray());
            },',');
            $entries     = $roles->whereIn('slug', $targetRoles);
        } elseif ($entryType === 'user') {
            $users       = $userRepository->all();
            $targetUsers = $this->argumentElse('entryIds', function () use ($users) {
                return $this->select('Target users', $users->pluck('username')->toArray());
            },',');
            $entries     = $users->whereIn('username', $targetUsers);
        }

        $permissionSet = new PermissionSet();

        $by = $this->argumentElse('by', function () {
            return $this->choice('Select permissions by', [ 'set', 'permission', 'addon' ], 'permission');
        });
        if ($by === 'set') {
            $selected = $this->argumentElse('byVal', function () use ($sets) {
                return $this->select('Select sets', $sets->keys()->toArray());
            },',');
            $sets->only($selected);
            $permissionSet = $sets->only($selected)->combineToSet();
        } elseif ($by === 'permission') {
            $permissions = $addons->installed()->withConfig('permissions')->map(function (Addon $addon) {
                return $this->getAddonPermissions($addon);
            })->flatten();

            if ($this->option('no-interaction')) {
                $selected = [ 'anomaly.module.settings::settings.write', 'anomaly.module.dashboard::dashboards.write' ];
            } else {
                $selected = $this->argumentElse('byVal', function () use ($permissions){
                    return $this->select('Permissions', $permissions->toArray());
                },',');
            }
            $permissionSet->add($selected);
        } elseif ($by === 'addon') {
            $addonNamespaces = $addons->installed()->withConfig('permissions')->map->getNamespace();
            $selected        = $this->argumentElse('byVal', function () use ($addonNamespaces) {
                return $this->select('Addons', $addonNamespaces);
            },',');
            foreach ($selected as $addonNamespace) {
                $addon = $addons->get($addonNamespace);
                $permissionSet->add($this->getAddonPermissions($addon));
            }
        }

        $this->info("Adding: \n - " . implode("\n - ", $permissionSet->toArray()));
        $this->line('');
        $this->info('To ' . $entryType . 's: ' . $entries->map->getKey());

//        $this->dump('entries', $entries);
//        $this->dump('permissions', $permissions);

        foreach ($entries as $entry) {
            $permissionSet->saveToEntry($entry);
        }
    }

    protected function argumentElse($argument, Closure $closure, $split=null)
    {
        $argument = $this->argument($argument);
        if ($argument !== null) {
            if($split){
                $argument = explode($split, $argument);
            }
            return $argument;
        }
        return $closure();
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
