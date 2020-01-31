<?php

namespace Anomaly\Streams\Platform\Support;

use GeneratedHydrator\Configuration;
use Illuminate\Filesystem\Filesystem;

class Hydrator
{
    static $ensuredDirectory = false;

    static $targetDir;

    static $hydrators = [];

    static $initTime = 0;

    /**
     * Hydrate an object with parameters.
     *
     * @param        $object
     * @param array  $parameters
     *
     * @return mixed
     */
    public function hydrate2($object, array $parameters)
    {
        $class = get_class($object);

        if (static::$targetDir === null) {
            static::$targetDir = storage_path('hydrators');
        }
        if (static::$ensuredDirectory !== true) {
            $fs = new Filesystem();
            if ( ! $fs->exists(static::$targetDir)) {
                $fs->makeDirectory(static::$targetDir, 0755, true);
            }
            static::$ensuredDirectory = true;
        }

        if ( ! array_key_exists($class, static::$hydrators)) {
            $start  = microtime(true);
            $config = new Configuration($class);
            $config->setGeneratedClassesTargetDir(static::$targetDir);
            /** @var HydratorInterface $hydrator */
            $hydratorClass               = $config->createFactory()->getHydratorClass();
            static::$hydrators[ $class ] = new $hydratorClass();
            static::$initTime            += microtime(true) - $start;
        }

        static::$hydrators[ $class ]->hydrate($parameters, $object);
    }

    /**
     * Hydrate an object with parameters.
     *
     * @param        $object
     * @param array  $parameters
     *
     * @return mixed
     */
    public function hydrate($object, array $parameters)
    {
        foreach ($parameters as $parameter => $value) {
            $method = camel_case('set_' . $parameter);

            if (method_exists($object, $method)) {
                $object->{$method}($value);
            }
        }

        return $object;
    }
}
