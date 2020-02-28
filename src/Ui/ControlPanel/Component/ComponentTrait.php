<?php

namespace Pyro\Platform\Ui\ControlPanel\Component;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait ComponentTrait
{
    public function translate($keys, $recursive = false)
    {
        foreach (Arr::wrap($keys) as $key) {
            $set = Str::camel('set_' . $key);
            $get = Str::camel('get_' . $key);
            $this->$set(trans($this->$get()));
        }
        if ($recursive && count($this->getChildren()) > 0) {
            foreach ($this->getChildren() as $child) {
                if (method_exists($child, 'translate')) {
                    $child->translate($keys, $recursive);
                }
            }
        }
        return $this;
    }
}
