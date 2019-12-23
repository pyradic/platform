<?php

namespace Support;

class Util
{
    /**
     * @param string|object $classOrObject
     * @param string        $trait The trait's FQNS
     *
     * @return boolean
     */
    public static function usesTrait($classOrObject, string $trait)
    {
        if (is_object($classOrObject)) {
            $classOrObject = get_class($classOrObject);
        }
        return in_array($trait, class_uses_deep($classOrObject), true);
    }
}
