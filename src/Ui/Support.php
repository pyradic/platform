<?php

namespace Pyro\Platform\Ui;

use Anomaly\Streams\Platform\Support\Authorizer;
use Anomaly\Streams\Platform\Support\Evaluator;
use Anomaly\Streams\Platform\Support\Hydrator;
use Anomaly\Streams\Platform\Support\Parser;
use Anomaly\Streams\Platform\Support\Resolver;
use Anomaly\Streams\Platform\Support\Template;
use Anomaly\Streams\Platform\Support\Value;
use Illuminate\Support\Collection;
use Pyro\Platform\Support\ExpressionLanguageParser;

class Support
{
    public static function expression($target, array $arguments = [])
    {
        return ExpressionLanguageParser::getInstance()->parse($target, $arguments);
    }

    public static function resolver($target, array $arguments = [], array $options = [])
    {
        $resolver = static::resolve(Resolver::class);
        $target   = $resolver->resolve($target, $arguments, $options);
        if (is_array($target)) {
            foreach ($target as &$item) {
                $item = $resolver->resolve((array)$item, $arguments, $options);
//                if (is_array($item)) {
//                    foreach ($item as $key => $value) {
//                        $item[ $key ] = static::resolver($value, $arguments, $options);
//                    }
//                }
            }
        }
        return $target;
    }

    public static function render(&$target, $entry, $term = 'entry', $payload = [])
    {
        if (is_array($target)) {
            foreach ($target as &$item) {
                $item = static::render($item, $entry, $term, $payload);
            }
        } elseif (is_string($target) && str_contains($target, [ '{{', '{%' ])) {
            $target = (string)static::resolve(Template::class)->render($target, [ $term => $entry ]);
        }
        return $target;
    }

    protected static $resolved = [];

    protected static function resolve($name)
    {
        if ( ! array_key_exists($name, static::$resolved)) {
            static::$resolved[ $name ] = resolve($name);
        }
        return static::$resolved[ $name ];
    }

    public static function evaluate($target, array $arguments = [])
    {
        return  static::resolve(Evaluator::class)->evaluate($target, $arguments);
    }

    public static function valuate(&$parameters, $entry, $term = 'entry', $payload = [])
    {
        return $parameters = static::resolve(Value::class)->make($parameters, $entry, $term = 'entry', $payload = []);
    }

    public static function parse(&$target, array $data = [])
    {
        return $target = static::resolve(Parser::class)->parse($target, $data);
    }

    public static function hydrate($object, array $parameters)
    {
        return static::resolve(Hydrator::class)->hydrate($object, $parameters);
    }

    public static function authorize($permission, $user = null)
    {
        return static::resolve(Authorizer::class)->authorize($permission, $user);
    }

    public static function translate(&$target)
    {

        if (is_string($target) && strpos($target, '::')) {

            if ( ! trans()->has($target)) {
                return $target;
            }

            return $target = trans($target);
        }

        if (is_array($target)) {
            foreach ($target as &$item) {
                $item = static::translate($item);
            }
        }

        if ($target instanceof Collection) {
            $target = $target->map(function ($item) {
                return static::translate($item);
            });
        }

        return $target;
    }
}
