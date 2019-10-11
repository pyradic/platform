<?php

namespace Pyro\Platform\Database;

use Anomaly\Streams\Platform\Traits\FiresCallbacks;
use Faker\Factory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;

class Seeder extends \Anomaly\Streams\Platform\Database\Seeder\Seeder
{
    use FiresCallbacks;
    use WithFaker;

    public static function when($trigger, $callback)
    {
        $trigger = static::class . '::' . $trigger;

        if (!isset(self::$listeners[$trigger])) {
            self::$listeners[$trigger] = [];
        }

        self::$listeners[$trigger][] = $callback;
    }

    public function getFaker()
    {
        return $this->faker;
    }

    protected function makeFaker($locale = null)
    {
        return Factory::create($locale ?? 'nl_NL');
    }

    public function __construct()
    {
        parent::__construct();
        $this->setUpFaker();
        $this->fire('constructed');
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

            $instance->__invoke();

            $this->fire('called', [ $this, $instance, $class, $silent ]);
        }


        return $this;
    }
}
