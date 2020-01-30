<?php

namespace Pyro\Platform;

use Exception;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Arr;

class Hooks
{
    protected static $handlers = [];

    /**
     * @var \Pyro\Platform\HookDispatchHandler
     */
    protected static $dispatchHandler;

    /**
     * @param string|string[]|array $id
     * @param                       $handler
     */
    public static function register($id, $handler)
    {
        foreach (Arr::wrap($id) as $_id) {
            static::$handlers[ $_id ][] = $handler;
        }
    }

    public static function isRegistered($id)
    {
        return ! empty(static::$handlers[ $id ]);
    }

    public static function clear($name)
    {
        unset(self::$handlers[ $name ]);
    }

    public static function getHandlers($id = null)
    {
        if ($id === null) {
            return static::$handlers;
        }
        if ( ! static::isRegistered($id)) {
            return [];
        }
        return static::$handlers[ $id ];
    }

    public static function run($id, array $args = [], $abortable = false)
    {
        if ($id !== 'hooks.run') {
            static::run('hooks.run', [ $id, $args, $abortable ]);
        }
        foreach (static::getHandlers($id) as $handler) {
//            $retval = app()->call($handler, $args);
            $retval = call_user_func_array($handler, $args);
            if ($abortable === false && $retval !== null && $retval !== true) {
                throw new Exception("Invalid return from hook [{$id}] handler");
            }
            if ($retval === null) {
                continue;
            }
            if (is_string($retval)) {
                // String returned means error.
                throw new Exception($retval);
            }
            if ($retval === false) {
                // False was returned. Stop processing, but no error.
                return false;
            }
        }
        return true;
    }

    public static function waterfall($id, $value, array $args = [])
    {
        if ($id !== 'hooks.waterfall') {
            static::run('hooks.waterfall', [ $id, $value, $args ]);
        }
        $pipes = collect(static::getHandlers($id));

        $pipes = $pipes->map(function ($hook) use ($args) {

            return function ($value, $next) use ($hook, $args) {
                if (is_callable($hook)) {
                    return $next($hook($value, ...$args));
                }
                return $next($value);
            };
        })
            ->all();

        return with(new Pipeline())
            ->send($value)
            ->through($pipes)
            ->then(function ($value) {
                return $value;
            });
    }

    public static function dispatch($class, array $arguments = [])
    {
        $class   = Arr::wrap($class);
        $command = $class[ 0 ];
        $caller  = $class[ 1 ] ?? null;

        $handlers = collect()->merge(static::getHandlers($command));
        if ($caller) {
            $handlers = $handlers->merge(static::getHandlers($command . ':' . $caller));
        }

        $dispatch = new HookDispatch($command, $arguments, $caller);

        return with(new Pipeline())
            ->send($dispatch)
            ->through($handlers->toArray())
            ->then(function ($dispatch) {
                return dispatch_now($dispatch, static::getDispatchHandler());
            });
    }

    protected static function getDispatchHandler()
    {
        if (static::$dispatchHandler === null) {
            static::$dispatchHandler = new HookDispatchHandler(app());
        }
        return static::$dispatchHandler;
    }
}
