<?php

namespace Pyro\Platform\Ui\ControlPanel\Component;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use Laradic\Support\Traits\ArrayAccessibleProperties;

class Shortcut extends \Anomaly\Streams\Platform\Ui\ControlPanel\Component\Shortcut\Shortcut implements Arrayable, ArrayAccess
{
    use ArrayAccessibleProperties;

    protected $children = [];

    protected $type = 'default';

    public function __construct()
    {
        return;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function setChildren($children)
    {
        $this->children = $children;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function toArray()
    {
        return [];
//        return [
//            'slug'        => $this->slug,
//            'icon'        => $this->icon,
//            'title'       => trans($this->title),
//            'label'       => trans($this->label),
//            'class'       => $this->class,
//            'highlighted' => $this->highlighted,
//            'context'     => $this->context,
//            'attributes'  => $this->attributes,
//            'permission'  => $this->permission,
//            'type'        => $this->type,
//            'children'    => $this->children,
//        ];
    }

}
