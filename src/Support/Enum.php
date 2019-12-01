<?php

namespace Pyro\Platform\Support;

class Enum extends \MabeEnum\Enum
{
    public static function generateDocblock()
    {
        $class    = new \ReflectionClass(static::class);
        $docblock = collect($class->getConstants())->map(function ($value, $key) use ($class) {
            return " * @method static {$class->getShortName()} {$key}()";
        })->implode("\n");
        return $docblock;
    }
}
