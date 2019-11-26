<?php

namespace Pyro\Platform\Ui\ControlPanel\Component;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use Laradic\Support\Traits\ArrayAccessibleProperties;

class Shortcut extends \Anomaly\Streams\Platform\Ui\ControlPanel\Component\Shortcut\Shortcut implements Arrayable, ArrayAccess
{
    use ArrayAccessibleProperties;

    protected $children = [];

    public function getChildren()
    {
        return $this->children;
    }

    public function setChildren($children)
    {
        $this->children = $children;
        return $this;
    }

    public function toArray()
    {
        return [
            'slug'        => $this->slug,
            'icon'        => $this->icon,
            'title'       => trans($this->title),
            'label'       => trans($this->label),
            'class'       => $this->class,
            'highlighted' => $this->highlighted,
            'context'     => $this->context,
            'attributes'  => $this->attributes,
            'permission'  => $this->permission,
            'children'    => $this->children,
        ];
    }

}
