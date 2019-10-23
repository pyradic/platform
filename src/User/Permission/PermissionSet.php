<?php

namespace Pyro\Platform\User\Permission;

use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;
use ArrayIterator;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use IteratorAggregate;

class PermissionSet implements Arrayable, IteratorAggregate
{
    use Macroable;

    protected $permissions = [];

    public function __construct(array $permissions = [])
    {
        $this->permissions = $permissions;
    }


    public function applyToEntry(EntryInterface $entry, $key = 'permissions')
    {
        $permissions = $entry->getAttribute($key);
        $permissions = array_unique(array_merge($permissions, $this->permissions));
        $entry->setAttribute('permissions', $permissions);
        return $this;
    }

    public function saveToEntry(EntryInterface $entry, $key = 'permissions')
    {
        $this->applyToEntry($entry, $key);
        $entry->save();
        return $this;
    }

    /**
     * @param string|string[]|array|Collection $permissions
     *
     * @return $this
     */
    public function add($permissions)
    {
        foreach (Collection::wrap($permissions) as $permission) {
            if ( ! $this->has($permission)) {
                $this->permissions[] = $permission;
            }
        }
        return $this;
    }

    public function has($permission)
    {
        if ( ! $permission) {
            return true;
        }

        if (in_array($permission, $this->permissions, true)) {
            return true;
        }

        return false;
    }

    public function remove($permissions)
    {
        $permissions       = Arr::wrap($permissions);
        $this->permissions = array_filter($this->permissions, function (string $permission) use ($permissions) {
            return in_array($permission, $permissions, true) === false;
        });
        return $this;
    }

    public function setPermissions(array $permissions)
    {
        $this->permissions = $permissions;
        return $this;
    }

    public function getPermissions()
    {
        return $this->permissions;
    }

    public function toArray()
    {
        return $this->permissions;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->permissions);
    }
}
