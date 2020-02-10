<?php

namespace Pyro\Platform\Ui\Hyperscript;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use Laradic\Support\Traits\ArrayAccessibleProperties;

class Element implements Arrayable, ArrayAccess
{
    use ArrayAccessibleProperties;

    public $tag;

    /** @var \Pyro\Platform\Ui\Hyperscript\ElementAttributesCollection */
    public $attributes;

    /** @var \Pyro\Platform\Ui\Hyperscript\ElementCollection */
    public $children;

    public $text;

    public function __construct($tag = null, $attributes = [], $children = null)
    {
        $this->tag        = $tag ?? $this->tag ?? 'div';
        $this->attributes = array_replace_recursive($this->attributes ?? [], $attributes ?? []);
        $this->children   = [];
        if (is_string($children)) {
            $this->text = $children;
        } elseif (is_array($children)) {
            $this->children = $children;
        }
    }

    public static function extend($attrs = [], $children = [])
    {
        return new static(null, $attrs = [], $children = []);
    }

    public function walk(\Pyro\Platform\Ui\Hyperscript\ElementVisitor $visitor)
    {
        (new ElementWalker())->walk($this, $visitor);
    }

    public static function make($tag, $attrs = [], $children = [])
    {
        return new static($tag, $attrs = [], $children = []);
    }

    public function add($child)
    {

    }

    public function getTag()
    {
        return $this->tag;
    }

    public function setTag($tag)
    {
        $this->tag = $tag;
        return $this;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
        return $this;
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

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    public function toArray()
    {
        return [
            'tag'        => $this->tag,
            'attributes' => $this->attributes,
            'children'   => $this->children,
            'text'       => $this->text,
        ];
    }
}
