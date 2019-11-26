<?php

namespace Pyro\Platform\Ui\ControlPanel\Component;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Pyro\Platform\Command\GetClassArray;

trait ArrayableComponent
{
    public function toArray()
    {
        $array =  dispatch_now(new GetClassArray($this));
        return $array;
    }

}
