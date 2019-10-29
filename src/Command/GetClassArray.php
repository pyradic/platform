<?php

namespace Pyro\Platform\Command;

use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;

class GetClassArray
{
    /** @var object */
    protected $instance;

    /**
     * GetClassArray constructor.
     *
     * @param object $instance
     */
    public function __construct($instance)
    {
        $this->instance = $instance;
    }


    public function handle()
    {
        $data    = [];
        $class   = new ReflectionClass(get_class($this->instance));
        $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
        foreach ($methods as $method) {
            $methodName = $method->getName();
            if (Str::startsWith($methodName, [ 'get', 'is' ])) {
                $name          = preg_replace('/^(get|is)/', '', $methodName);
                $name          = Str::camel($name);
                $data[ $name ] = call_user_func([ $this->instance, $methodName ]);
            }
        }
        return $data;
    }
}
