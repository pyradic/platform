<?php

namespace Pyro\Platform\Database;

use Anomaly\Streams\Platform\Traits\FiresCallbacks;
use Illuminate\Support\Arr;
use Pyro\Platform\Database\Event\InvokedSeeder;
use Pyro\Platform\Database\Event\InvokeSeeder;

class Seeder extends \Anomaly\Streams\Platform\Database\Seeder\Seeder
{
    use FiresCallbacks;
    use WithFaker;

    public static $result;

    public static $registered = [];

    protected static $helpers = [];

    protected static $name;

    protected static $description = '';

    public function setResult($result)
    {
        static::$result = $result;
        return $this;
    }

    public static function registerSeed($name = null, $description = null)
    {
        $name         = $name ?? static::$name;
        static::$name = $name;

        $description         = $description ?? static::$description;
        static::$description = $description;

        $class = static::class;
        if ($name === null) {
            $name = $class;
        }
        self::$registered[ $name ] = compact('name', 'class', 'description');
    }

    protected function option($key)
    {
        if ($this->command) {
            return $this->command->option($key);
        }
        return null;
    }

    protected function argument($key)
    {
        if ($this->command) {
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

    public function error($message)
    {
        if ($this->command) {
            return $this->command->error($message);
        }
        throw new \RuntimeException($message);
    }

    public function write($messages)
    {
        if ($this->command) {
            $this->command->getOutput()->write($messages);
        }
    }

    public function line($messages, $verbosity = null)
    {
        if ($this->command) {
            $this->command->line($messages, null, $verbosity);//getOutput()->writeln($messages);
        }
    }

    public function confirm($message, $default = false)
    {
        if ($this->command) {
            return $this->command->confirm($message, $default);
        }
        return $default;
    }

    public function input($message, $default = null)
    {
        if ($this->command) {
            return $this->command->ask($message, $default);
        }
        return $default;
    }

    public function helper($class)
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

            $result = $instance->__invoke($silent);

            if (isset($result)) {
                $this->setResult($result);
            }

            $this->fire('called', [ $this, $instance, $class, $result ]);
        }

        return $this;
    }

    public function __invoke($silent = false)
    {
        $class = get_class($this);

        event(new InvokeSeeder($this));
        $this->fire('invoke', [ $this, $class, $silent ]);

        $result = parent::__invoke();

        if (isset($result)) {
            $this->setResult($result);
        }

        event(new InvokedSeeder($this, $result));
        $this->fire('invoked', [ $this, $class, $result ]);

        return $result;
    }

    public function getCommand()
    {
        return $this->command;
    }
}
