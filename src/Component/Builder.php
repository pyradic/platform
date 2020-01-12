<?php

namespace Pyro\Platform\Component;

use ReflectionClass;

/**
 * @mixin \Pyro\Platform\Component\Component
 * @method Builder table(string|array $data = Builder::_dataExample())
 * @method Builder tr(string|array $data = Builder::_dataExample())
 * @method Builder td(string|array $data = Builder::_dataExample())
 * @method Builder pyToolbar(string|array $data = Builder::_dataExample())
 * @method Builder pyToolbarItem(string|array $data = Builder::_dataExample())
 * @method Builder pyToolbarTitle(string|array $data = Builder::_dataExample())
 * @method Builder pyToolbarToggle(string|array $data = Builder::_dataExample())
 * @method Builder pyMenu(string|array $data = Builder::_dataExample())
 * @method Builder pyMenuItem(string|array $data = Builder::_dataExample())
 * @method Builder pyMenuSubmenu(string|array $data = Builder::_dataExample())
 * @method Builder elRow(string|array $data = Builder::_dataExample())
 * @method Builder elCol(string|array $data = Builder::_dataExample())
 * @method Builder elButton(string|array $data = Builder::_dataExample())
 * @method Builder a(string|array $data = Builder::_dataExample())
 * @method Builder div(string|array $data = Builder::_dataExample())
 */
class Builder
{
    /** @var null|Builder */
    public $parent;

    protected $children = [];

    /** @var string */
    protected $tag;

    /** @var \Pyro\Platform\Component\Component */
    protected $component;

    public function __construct(string $tag, $parent = null)
    {
        $this->tag       = $tag;
        $this->component = new Component();
        $this->component->setTag($tag);
        if ($parent) {
            $this->setParent($parent);
        }
    }

    public static function _dataExample()
    {
        return [ 'props' => [], 'attrs' => [], 'class' => [], 'children' => [ null => static::_dataExample() ] ];
    }

    public function getComponent()
    {
        return $this->component;
    }

    /**
     * @param Builder|null $parent
     *
     * @return void
     */
    public function setParent($parent = null)
    {
        if ($this->parent === $parent) {
            return $this;
        }
        if ($this->getParent() && $this->getParent()->hasChild($this)) {
            $this->getParent()->removeChild($this);
        }
        if ($parent !== null) {
            $parent->addChild($this);
            $this->component->setParent($parent->getComponent());
        }

        $this->parent = $parent;
        return $this;
    }

    /**
     * @param Builder $child
     *
     * @return $this
     */
    public function addChild($child)
    {
        if ($this->hasChild($child)) {
            return $this;
        }
        $this->children[] = $child;
        $child->setParent($this);
        return $this;
    }

    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Builder $child
     *
     * @return bool
     */
    public function hasChild($child): bool
    {
        foreach ($this->children as $item) {
            if ($item === $child) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param Builder $child
     *
     * @return $this
     */
    public function removeChild($child)
    {
        foreach ($this->children as $key => $myChild) {
            if ($child == $myChild) {
                unset($this->children[ $key ]);
            }
        }

        $this->children = array_values($this->children);

        $child->setParent(null);

        return $this;
    }

    /** @var \ReflectionClass */
    protected static $reflection;

    protected function getComponentReflection()
    {
        if (static::$reflection === null) {
            static::$reflection = new ReflectionClass(Component::class);
        }
        return static::$reflection;
    }

    public static function make(string $tag, $parent = null)
    {
        return new static($tag, $parent);
    }

    public function up()
    {
        return $this->parent;
    }

    public function __call($name, $arguments)
    {
        if (
            method_exists(Component::class, $name)
            && $this->getComponentReflection()->hasMethod($name)
            && $this->getComponentReflection()->getMethod($name)->isPublic()
        ) {
            return $this->component->{$name}(...$arguments);
        }
        $instance         = new static($name, $this);
        $this->children[] = $instance;
        if(count($arguments) > 0){
            if(is_string($arguments[0])){
                $instance->getComponent()->setValue($arguments[0]);
            } elseif(is_array($arguments[0])){
                $instance->getComponent()->reset($arguments[0]);
            }
        }
        return $instance;
    }

    public function __get($name)
    {
        $this->component[ $name ];
    }

    public function __set($name, $value)
    {
        // TODO: Implement __set() method.
    }

    public function __isset($name)
    {
        // TODO: Implement __isset() method.
    }

    public function __unset($name)
    {
        // TODO: Implement __unset() method.
    }

    public function __toString()
    {
        return '';
    }

    public function __invoke()
    {
        // TODO: Implement __invoke() method.
    }

    public function __clone()
    {
        // TODO: Implement __clone() method.
    }

}
