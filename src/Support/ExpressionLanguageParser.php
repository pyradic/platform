<?php

namespace Pyro\Platform\Support;

use BadMethodCallException;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Laradic\Support\Dot;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;

class ExpressionLanguageParser
{
    /** @var ExpressionLanguageParser */
    protected static $instance;

    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = static::createDefault();
            foreach (static::$registerFunctions as $fn) {
                static::$instance->registerFunction($fn, function (...$params) use ($fn) {
                    return $fn(...$params);
                });
            }
        }
        return static::$instance;
    }

    protected static $registerFunctions = [];

    public static function registerFunctions(array $fns)
    {
        static::$registerFunctions = array_unique(array_merge(static::$registerFunctions[], $fns));
    }

    /** @var \Symfony\Component\ExpressionLanguage\ExpressionLanguage */
    protected $exl;

    /** @var \Laradic\Support\Dot */
    protected $data;

    /** @var string[] */
    protected $excludes = [];

    /** @var string[] */
    protected $includes = [];

    public function __construct(ExpressionLanguage $exl)
    {
        $this->exl  = $exl;
        $this->data = new Dot();
    }

    /**
     * @param string|string[] $patterns
     */
    public function exclude($patterns)
    {
        return $this->mergeUnique($this->excludes, $patterns);
    }

    /**
     * @param string|string[] $patterns
     */
    public function include($patterns)
    {
        return $this->mergeUnique($this->includes, $patterns);
    }

    protected function mergeUnique(&$target, $items)
    {
        $items  = Arr::wrap($items);
        $target = array_unique(array_merge($target, $items));
        return $this;
    }

    public function shouldParse($key)
    {
        $excluded = $this->isExcluded($key);
        $included = $this->isIncluded($key);

        if ( ! $excluded && ! $included) {
            return true;
        }
        if ($excluded && ! $included) {
            return false;
        }
        if ( ! $excluded && $included) {
            return true;
        }
        if ($excluded && $included) {
            return true;
            throw new Exception('shouldParse calculation not yet implemented');
        }
        return false;
    }

    protected function isExcluded($key)
    {
        return $this->hasString($this->excludes, $key);
    }

    protected function isIncluded($key)
    {
        return $this->hasString($this->includes, $key);
    }

    public function setExcludes($excludes)
    {
        $this->excludes = $excludes;
        return $this;
    }

    public function setIncludes($includes)
    {
        $this->includes = $includes;
        return $this;
    }

    protected function hasString($patterns, $string, $count = false)
    {
        $items = array_filter($patterns, static function ($value) use ($string) {
            $pattern = (string)$value;
            return Str::is($pattern, $string);
        }, ARRAY_FILTER_USE_BOTH);
        return $count ? count($items) : count($items) > 0;
    }

    public function parse($target, $data = [], $key = null)
    {
        if ( ! $this->shouldParse($key)) {
            return $target;
        }

        $data = $this->prepareData($data);

        /*
         * If the target is an array
         * then parse it recursively.
         */
        if (is_array($target)) {
            foreach ($target as $k => &$value) {
                $value = $this->parse($value, $data, $key === null ? $k : $key . '.' . $k);
            }
        }

        /*
         * if the target is a string and is in a parsable
         * format then parse the target with the payload.
         */
        if (is_string($target) && Str::contains($target, [ '{{', '}}' ])) {
            $target = $this->evaluate($target, $data);
        }
        return $target;
    }

    protected function evaluate($value, $data = [])
    {
        $matched = preg_match_all('/\{\{(.*?)\}\}/', $value, $matches);
        if ( ! $matched) {
            return $value;
        }

        foreach ($matches[ 0 ] as $i => $original) {
            $expression = trim($matches[ 1 ][ $i ]);
            $result     = $this->exl->evaluate($expression, $data);
            $result     = $this->parse($result, $data);
            $value      = str_replace($original, $result, $value);
        }
        return $value;
    }

    protected function prepareData($data)
    {
        $prepared = $this->data->dot();
        $prepared->mergeRecursive($data);
        return $prepared->toArray();
    }

    public function registerFunction($name, $callback)
    {
        $this->exl->register($name, static function (...$params) {
            throw new BadMethodCallException('compile not implemented');
        }, static function ($arguments, ...$params) use ($name, $callback) {
            return $callback(...$params);
        });
        return $this;
    }

    public function registerPhpFunction($phpName, $expName = null)
    {
        $this->exl->addFunction(ExpressionFunction::fromPhp($phpName, $expName));
        return $this;
    }

    public function registerPhpFunctions(array $names)
    {
        collect($names)->call([ $this, 'registerPhpFunction' ], [], false);
        return $this;
    }

    public function registerClassMethods($class)
    {
        try {
            if ( ! $class instanceof ReflectionClass) {
                $class = new ReflectionClass($class);
            }
            foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                $methodName = $method->getName();
                $this->exl->register($method->getName(), static function (...$params) {
                    throw new BadMethodCallException('compile not implemented');
                }, static function ($arguments, ...$params) use ($methodName) {
                    return $arguments->{$methodName}(...$params);
                });
            }

//            if (Str::contains($class->getDocComment(), '@mixin')) {
//                if (preg_match('/@mixin (.*)/', $class->getDocComment(), $matches) === 1 && isset($matches[ 1 ]) && class_exists($matches[ 1 ])) {
//                    static::registerClassMethods($exl, $matches[ 1 ]);
//                }
//            }
        }
        catch (ReflectionException $e) {
        }
        return $this;
    }

    public function getExpressionLanguage()
    {
        return $this->exl;
    }

    public static function createDefault()
    {
        $cache = new \Madewithlove\IlluminatePsrCacheBridge\Laravel\CacheItemPool(resolve('cache.store'));
        return new ExpressionLanguageParser(new ExpressionLanguage($cache));
    }
}
