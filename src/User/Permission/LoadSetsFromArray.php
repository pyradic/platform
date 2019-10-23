<?php

namespace Pyro\Platform\User\Permission;

use Anomaly\AddonsModule\Addon\AddonCollection;
use Illuminate\Contracts\Config\Repository;

class LoadSetsFromArray
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function handle(AddonCollection $addons, Repository $config)
    {
        $sets = [];
        foreach ($this->data as $name => $constraints) {
            $set           = new PermissionSet();
            $sets[ $name ] = $set;
            foreach ($constraints as $constraint) {
                $namespace   = head(explode('::', $constraint));
                $permissions = collect($config->get($namespace . '::permissions'));
                $all         = collect();
                foreach ($permissions as $group => $slugs) {
                    foreach ($slugs as $slug) {
                        $all->push("{$namespace}::{$group}.{$slug}");
                    }
                }
                $matches = $all->matchingString($constraint);
                $set->add($matches);
            }
        }
        return $sets;
    }
}
