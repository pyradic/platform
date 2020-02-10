<?php

namespace Pyro\Platform\Ui\Hyperscript;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Hyperscript
{
    /**
     * @param string|\Pyro\Platform\Ui\Hyperscript\Element                                                    $tag
     * @param \Pyro\Platform\Ui\Hyperscript\Element|\Pyro\Platform\Ui\Hyperscript\Element[]|string|array|null $attributesOrChildren
     * @param \Pyro\Platform\Ui\Hyperscript\Element|\Pyro\Platform\Ui\Hyperscript\Element[]                   $children
     *
     * @return \Pyro\Platform\Ui\Hyperscript\Element
     */
    public static function createElement($tag, ...$params)
    {
        $el         = static::resolveElementFromTag($tag);
        $children   = [];
        $text       = null;
        $attributes = [];

        if ($params[ 0 ]) {
            if (is_array($params[ 0 ])) {
                if (Arr::isAssoc($params[ 0 ])) {
                    $attributes = $params[ 0 ];
                } else {
                    $children = $params[ 0 ];
                }
            } elseif (is_string($params[ 0 ])) {
                $text = $params[ 0 ];
            }
        }
        if (isset($params[ 1 ])) {
            if (is_string($params[ 1 ])) {
                $text = $params[ 1 ];
            } elseif (is_array($params[ 1 ])) {
                $children = $params[ 1 ];
            }
        }

        $el->setAttributes($attributes);
        $el->setChildren($children);
        $el->setText($text);

        return $el;
    }

    public static function resolveElementFromTag($tag)
    {
        if ($tag instanceof \Pyro\Platform\Ui\Hyperscript\Element) {
            $el = clone($tag);
        } elseif (Str::contains($tag, '\\') && class_exists($tag)) {
            $el = new $tag();
        } else {
            $el = new \Pyro\Platform\Ui\Hyperscript\Element($tag);
        }
        return $el;
    }

    public static function resolveAttributesFromTag($tag)
    {
    }

}
