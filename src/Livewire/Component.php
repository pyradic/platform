<?php

namespace Pyro\Platform\Livewire;

use Illuminate\Support\Str;

abstract class Component extends \Livewire\Component
{
    protected $name;

    public function getName()
    {
        if($this->name){
            return $this->name;
        }
        return Str::kebab((new \ReflectionClass(get_called_class()))->getShortName());
//        return parent::getName();
    }
}
