<?php

if ( ! function_exists('platform')) {
    /**
     * @return \Pyro\Platform\Platform
     */
    function platform()
    {
        return app('platform');
    }
}

if ( ! function_exists('get_class_properties_as_array')) {
    /**
     * @param object $instance
     *
     * @return array
     */
    function get_class_properties_as_array(object $instance)
    {
        return dispatch_now(new \Pyro\Platform\Command\GetClassArray($instance));
    }
}

if ( ! function_exists('get_protected_class_property')) {
    function get_protected_class_property(object $class, string $propertyName)
    {
        $reflection = new ReflectionClass($class);
        $property   = $reflection->getProperty($propertyName);
        if ($property->isPublic()) {
            return $class->{$propertyName};
        }
        $property->setAccessible(true);
        $value = $property->getValue($class);
        $property->setAccessible(false);
        return $value;
    }
}



if ( ! function_exists('h')) {
    /**
     * @param string|\Pyro\Platform\Ui\Hyperscript\Element                                                    $tag
     * @param \Pyro\Platform\Ui\Hyperscript\Element|\Pyro\Platform\Ui\Hyperscript\Element[]|string|array|null $attributesOrChildren
     * @param \Pyro\Platform\Ui\Hyperscript\Element|\Pyro\Platform\Ui\Hyperscript\Element[]                   $children
     *
     * @return \Pyro\Platform\Ui\Hyperscript\Element
     */
    function h($tag, $attributesOrChildren = null, $children = null)
    {
        return \Pyro\Platform\Ui\Hyperscript\Hyperscript::createElement($tag, $attributesOrChildren, $children);
    }
}
