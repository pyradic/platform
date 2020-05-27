<?php

namespace Pyro\Platform\Ui;

use Illuminate\Support\Collection;
use Pyro\Platform\Support\ExpressionLanguageParser;
use Pyro\Platform\Support\Facade\Authorizer;
use Pyro\Platform\Support\Facade\Evaluator;
use Pyro\Platform\Support\Facade\Hydrator;
use Pyro\Platform\Support\Facade\Parser;
use Pyro\Platform\Support\Facade\Resolver;
use Pyro\Platform\Support\Facade\Template;
use Pyro\Platform\Support\Facade\Value;

class Input
{
    /** @return ExpressionLanguageParser */
    public static function elp()
    {
        return resolve('expression_parser');
    }

    public static function expression($target, array $arguments = [])
    {
        return static::elp()->parse($target, $arguments);
    }

    public static function resolve($target, array $arguments = [], array $options = [])
    {
        $target = Resolver::resolve($target, $arguments, $options);
        if (is_array($target)) {
            foreach ($target as &$item) {

                $method = array_get($options, 'method', 'handle');
                if ((is_string($item) && str_contains($item, '@')) || $item instanceof \Closure) {
                    $item = app()->call($item, $arguments);
                } elseif (is_string($item) && class_exists($item) && method_exists($item, $method)) {
                    $item = app()->call($item . '@' . $method, $arguments);
                }
            }
        }
        return $target;
    }

    /**
     * @param $target
     * @param $data
     *
     * @return string|array
     */
    public static function render($target, $data)
    {
        if (is_array($target)) {
            foreach ($target as &$item) {
                $item = static::render($item, $data);
            }
        } elseif (is_string($target) && str_contains($target, [ '{{', '{%' ])) {
            $target = (string)Template::render($target, $data);
        }
        return $target;
    }

    public static function renderEntry($target, $entry, $term = 'entry', $payload = [])
    {
        if (is_array($target)) {
            foreach ($target as &$item) {
                $item = static::renderEntry($item, $entry, $term, $payload);
            }
        } elseif (is_string($target) && str_contains($target, [ '{{', '{%' ])) {
            $target = (string)Template::render($target, [ $term => $entry ]);
        }
        return $target;
    }

    public static function evaluate($target, array $arguments = [])
    {
        return Evaluator::evaluate($target, $arguments);
    }

    public static function valuate($parameters, $entry, $term = 'entry', $payload = [])
    {
        return Value::make($parameters, $entry, $term = 'entry', $payload = []);
    }

    public static function valuate2($parameters, $entry, $term = 'entry', $payload = [])
    {
        return resolve(Value2::class)->make($parameters, $entry, $term = 'entry', $payload = []);
    }

    public static function parse($target, array $data = [])
    {
        return Parser::parse($target, $data);
    }

    public static function hydrate($object, array $parameters)
    {
        return Hydrator::hydrate($object, $parameters);
    }

    public static function authorize($permission, $user = null)
    {
        return Authorizer::authorize($permission, $user);
    }

    public static function translate($target)
    {

        if (is_string($target) && strpos($target, '::')) {

            if ( ! trans()->has($target)) {
                return $target;
            }

            return trans($target);
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
