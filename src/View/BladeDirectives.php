<?php

namespace Pyro\Platform\View;

use Illuminate\Contracts\Container\Container;

abstract class BladeDirectives
{
    /** @var array */
    protected static $map = [];

//    protected static $map = [
//        'dothis', // dothis is also function in this class
//        'dothat' => 'functionNameInThisClass', // maps directive 'dothat' do 'functionNameInThisClass' in this class
//    ];
//    OR
//    protected static $map = [
//        'if'        => [
//            'ifthis', // dothis is also function in this class
//            'ifthat' => 'functionNameInThisClass', // maps directive 'dothat' do 'functionNameInThisClass' in this class
//        ],
//        'directive' => [
//            'dothis', // dothis is also function in this class
//            'dothat' => 'functionNameInThisClass', // maps directive 'dothat' do 'functionNameInThisClass' in this class
//        ],
//    ];

    public static function getMap()
    {
        return static::$map ?? [];
    }

    public static function registerDirectives(Container $container)
    {
        $class    = get_called_class();
        $compiler = $container->make('blade.compiler');
        foreach (static::getMap() as $key => $value) {
            if ($key === 'if' || $key === 'directive') {
                foreach ($value as $name => $callback) {
                    if (is_int($name)) {
                        $name = $callback;
                    }
                    $compiler->{$key}($name, [ $class, $value ]);
                }
            } else {
                if (is_int($key)) {
                    $key = $value;
                }
                $compiler->directive($key, [ $class, $value ]);
            }
        }
    }

}
