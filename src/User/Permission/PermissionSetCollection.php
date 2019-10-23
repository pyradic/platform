<?php

namespace Pyro\Platform\User\Permission;

use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Collection;

/**
 * @method PermissionSet get($name)
 * @method PermissionSet[] all()
 * @method PermissionSet[] toArray()
 */
class PermissionSetCollection extends Collection
{
    use DispatchesJobs;

    /** @var array|PermissionSet[] */
    protected $items = [];

    public function register(string $name, PermissionSet $set)
    {
        return $this->put($name, $set);
    }

    public function createAndRegister(string $name, array $permissions)
    {
        $set = new PermissionSet($permissions);
        $this->register($name, $set);
        return $set;
    }

    public function applyToEntry(EntryInterface $entry, $key = 'permissions')
    {
        foreach ($this->items as $item) {
            $item->applyToEntry($entry);
        }
        return $this;
    }

    public function saveToEntry(EntryInterface $entry, $key = 'permissions')
    {
        foreach ($this->items as $item) {
            $item->saveToEntry($entry);
        }
        return $this;
    }

    public function registerFromArray($data)
    {
        $sets = $this->dispatchNow(new LoadSetsFromArray($data));
        foreach ($sets as $name => $set) {
            $this->register($name, $set);
        }
        return $this;
    }

    public function combineToSet()
    {
        $set =new PermissionSet();
        foreach($this->items as $item){
            $set->add($item->getPermissions());
        }
        return $set;
    }

}
