<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace Pyro\Platform\Bus;


use Illuminate\Support\Traits\ForwardsCalls;
use ReflectionClass;

class CommandInspector
{
    use ForwardsCalls;

    protected $command;

    protected $result;

    protected $preventCall = false;

    public function __construct($command)
    {
        $this->command = $command;
    }

    public static function className($command)
    {
        return is_string($command) ? $command : get_class($command);
    }

    public function getClassName()
    {
        return static::className($this->command);
    }

    public function getCommand()
    {
        return $this->command;
    }

    public function getCommandProperty($name)
    {
        $class = new ReflectionClass($this->getClassName());
        if ( ! $class->hasProperty($name)) {
            throw new \InvalidArgumentException("Property [{$name}] does not exist on [$this->getClassName()}]");
        }
        $property = $class->getProperty($name);
        if ($property->isPublic()) {
            $value = $this->command->{$name};
        } elseif ($property->isProtected()) {
            $property->setAccessible(true);
            $value = $property->getValue($this->command);
            $property->setAccessible(false);
        } else {
            throw new \RuntimeException("Property [{$name}] was neither public or protected, can't get its value");
        }
        return $value;
    }

    public function setCommandProperty($name, $value)
    {
        $class = new ReflectionClass($this->getClassName());
        if ( ! $class->hasProperty($name)) {
            throw new \InvalidArgumentException("Property [{$name}] does not exist on [$this->getClassName()}]");
        }
        $property = $class->getProperty($name);
        if ($property->isPublic()) {
            $this->command->{$name} = $value;
        } elseif ($property->isProtected()) {
            $property->setAccessible(true);
            $property->setValue($this->command, $value);
            $property->setAccessible(false);
        } else {
            throw new \RuntimeException("Property [{$name}] was neither public or protected, can't set its value");
        }
        return $value;
    }

    public function __call($name, $arguments)
    {
        return $this->forwardCallTo($this->command, $name, $arguments);
    }


}
