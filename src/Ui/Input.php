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

class Input
{

    public static function expression($target, array $arguments = [])
    {
        return ExpressionLanguageParser::getInstance()->parse($target,$arguments);
    }

    public static function resolver($target, array $arguments = [], array $options = [])
    {
        $resolver = resolve(Resolver::class);
        $target   = $resolver->resolve($target, $arguments, $options);
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

    public static function render($target, $entry, $term = 'entry', $payload = [])
    {
        if (is_array($target)) {
            foreach ($target as &$item) {
                $item = static::render($item, $entry, $term, $payload);
            }
        } elseif (is_string($target) && str_contains($target, [ '{{', '{%' ])) {
            $target = (string)resolve(Template::class)->render($target, [ $term => $entry ]);
        }
        return $target;
    }

    public static function evaluate($target, array $arguments = [])
    {
        return resolve(Evaluator::class)->evaluate($target, $arguments);
    }

    public static function valuate($parameters, $entry, $term = 'entry', $payload = [])
    {
        return resolve(Value::class)->make($parameters, $entry, $term = 'entry', $payload = []);
    }

    public static function valuate2($parameters, $entry, $term = 'entry', $payload = [])
    {
        return resolve(Value2::class)->make($parameters, $entry, $term = 'entry', $payload = []);
    }

    public static function parse($target, array $data = [])
    {
        return resolve(Parser::class)->parse($target, $data);
    }

    public static function hydrate($object, array $parameters)
    {
        return resolve(Hydrator::class)->hydrate($object, $parameters);
    }

    public static function authorize($permission, $user = null)
    {
        return resolve(Authorizer::class)->authorize($permission, $user);
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
