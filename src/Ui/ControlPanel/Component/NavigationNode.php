<?php

namespace Pyro\Platform\Ui\ControlPanel\Component;

use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Navigation\NavigationLink;
use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Section\Section;
use Illuminate\Contracts\Support\Arrayable;
use Pyro\Platform\Ui\TreeNode\ValueNode;

class NavigationNode extends ValueNode implements Arrayable, \ArrayAccess
{

    /** @var string */
    protected $collectionClass = NavigationNodeCollection::class;

    protected $type;

//
//    public function getKey()
//    {
//        if($this->hasParent() && $this->getParent()->isRoot()){
//            return $this->getValue()->getSlug();
//        }
//        $parentKey = $this->getAncestors()->map(function(NavigationNode $node){
//            return $node->getValue()->getSlug();
//        })->implode('.');
//        return $parentKey . '.' . $this->getValue()->getSlug();
//    }

    public function createChild($value)
    {
        $child = new static();
        $child->setParent($this);
        $child->setValue($value);
        return $child;
    }

    public function setValue($value)
    {
        parent::setValue($value);
        if ($value instanceof NavigationLink) {
            $this->setType('navigation');
        } elseif ($value instanceof Section) {
            $this->setType('section');
        } elseif ($value instanceof \Anomaly\Streams\Platform\Ui\Button\Button) {
            $this->setType('button');
        } else {
            $this->setType('button');
        }
        return $this;
    }

    public function toArray()
    {
        $data = [];
        if ($this->getValue() instanceof Arrayable) {
            $data = $this->getValue()->toArray();
        }
        if ($this->hasChildren()) {
            $data[ 'children' ] = $this->getChildren()->toArray();
        }
        if ($type = $this->getType()) {
            $data[ '__type' ] = $type;
        }
        return $data;
    }

    public function fromArray(array $data, $parent = null)
    {
        if ($parent === null) {

            $parent = new NavigationNode();
        }
        $nodes= array_map(function ($item) use ($parent) {
            if ($item[ '__type' ] === 'navigation') {
                $instance = new \Pyro\Platform\Ui\ControlPanel\Component\NavigationLink(resolve('image'), resolve('Anomaly\Streams\Platform\Asset\Asset'));
            } elseif ($item[ '__type' ] === 'section') {
                $instance = new \Pyro\Platform\Ui\ControlPanel\Component\Section();
            } else {
                $instance = new \Pyro\Platform\Ui\ControlPanel\Component\Button();
            }
            \Hydrator::hydrate($instance, $item);
            $node = $parent->createChild($instance);
            if (isset($item[ 'children' ])) {
                $this->fromArray($item[ 'children' ], $node);
            }
            return $node;
        }, $data);
        return new NavigationNodeCollection($nodes);
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function __get($name)
    {
        return $this->getValue()->offsetGet($name);
    }

    public function __set($name, $value)
    {
        return $this->getValue()->offsetSet($name, $value);
    }

}
