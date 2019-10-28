<?php

namespace Pyro\Platform\Database;

use Anomaly\Streams\Platform\Traits\FiresCallbacks;
use Illuminate\Support\Arr;

class Seeder extends \Anomaly\Streams\Platform\Database\Seeder\Seeder
{
    use FiresCallbacks;
    use WithFaker;

    public static $result;

    public static $registered = [];

    protected static $helpers = [];

    protected static $name = '';

    protected static $description = '';

    public function setResult($result)
    {
        static::$result = $result;
        return $this;
    }

    public static function registerSeed($name = null, $description = null)
    {
        $name                      = $name ?? static::$name;
        static::$name              = $name;

        $description               = $description ?? static::$description;
        static::$description       = $description;

        $class                     = static::class;
        self::$registered[ $name ] = compact('name', 'class', 'description');
    }

    protected function option($key)
    {
        if($this->command) {
            return $this->command->option($key);
        }
        return null;
    }

    protected function argument($key)
    {
        if($this->command) {
            return $this->command->argument($key);
        }
        return null;
    }

    public static function getResult()
    {
        return static::$result;
    }

    public static function when($trigger, $callback)
    {
        $trigger = static::class . '::' . $trigger;

        if ( ! isset(self::$listeners[ $trigger ])) {
            self::$listeners[ $trigger ] = [];
        }

        self::$listeners[ $trigger ][] = $callback;
    }

    public function __construct()
    {
        parent::__construct();
        $this->setUpFaker();
        $this->fire('constructed');
    }

    protected function helper($class)
    {
        if ( ! array_key_exists($class, static::$helpers)) {
            static::$helpers[ $class ] = resolve($class);
        }
        return static::$helpers[ $class ];
    }

    /**
     * Seed the given connection from the given path.
     *
     * @param array|string $class
     * @param bool         $silent
     *
     * @return \Illuminate\Database\Seeder
     */
    public function call($class, $silent = false)
    {
        $classes = Arr::wrap($class);

        foreach ($classes as $class) {

            if ($silent === false && isset($this->command)) {
                $this->command->getOutput()->writeln("<info>Seeding:</info> $class");
            }

            $instance = $this->resolve($class);

            $this->fire('call', [ $this, $instance, $class, $silent ]);

            $result = $instance->__invoke();

            if (isset($result)) {
                $this->setResult($result);
            }

            $this->fire('called', [ $this, $instance, $class, $silent ]);
        }


        return $this;
    }


}
