<?php

namespace Pyro\Platform\Database;

/**
 * @mixin Seeder
 */
trait SeedCaller
{
//    protected static $seeds ;

    protected static $registerSeeds = true;

    public function run()
    {
        $results = [];
        foreach (static::$seeds as $key => $seed) {
            $results[ $key ] = $this->call($seed);
        }
        return $results;
    }

    public static function registerSeed($name = null, $description = null)
    {
        parent::registerSeed($name, $description);
        if(property_exists(static::class, 'seeds')) {
            if (static::$registerSeeds) {
                foreach (static::$seeds as $seed) {
                    $seed::registerSeed();
                }
            }
        }
    }
}
