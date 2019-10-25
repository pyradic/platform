<?php

namespace Pyro\Platform\Bus;

use Closure;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Str;

class Dispatcher extends \Illuminate\Bus\Dispatcher
{
    const EVENT_BEFORE = 'dispatching';
    const EVENT_AFTER = 'dispatched';

    /** @var \Illuminate\Events\Dispatcher */
    protected $events;

    public function __construct(Container $container, Closure $queueResolver = null)
    {
        parent::__construct($container, $queueResolver);
        $this->events = $container->make(\Illuminate\Contracts\Events\Dispatcher::class);
    }


    /**
     * Dispatch a command to its appropriate handler in the current process.
     *
     * @param mixed $command
     * @param mixed $handler
     *
     * @return mixed
     */
    public function dispatchNow($command, $handler = null)
    {
        $command = $this->fire(static::EVENT_BEFORE, $command);
        if($command !== false) {
            $result = parent::dispatchNow($command, $handler);
        }
        $this->fire(static::EVENT_AFTER, $command, $result);
        config()->push('bus.' . static::EVENT_AFTER, $command); // @todo remove this
        return $result;
    }

    protected function fire($name, $command, $result = null)
    {
        $inspect = function () use ($command) {
            return new CommandInspector($command);
        };
        $class   = is_string($command) ? $command : get_class($command);
        $payload = [ $command, $inspect,$result ];

        $name   = Str::ensureLeft($name, 'bus.');
        $return = $this->events->dispatch($name, $payload);

        // event('bus.dispatch: ' . $class, $command);
        $name   = Str::ensureRight($name, ': ' . $class);
        $return = $this->events->dispatch($name, $payload);

        return !$return || empty($return) ? $command : head($return);
    }

    protected static function listen($eventName, $listener)
    {
        return resolve(\Illuminate\Contracts\Events\Dispatcher::class)->listen($eventName, $listener);
    }

    public static function before($command, $listener = null)
    {
        $eventName = 'bus.' . self::EVENT_BEFORE;
        if ($listener === null) {
            return static::listen($eventName, $command);
        }
        $class = is_string($command) ? $command : get_class($command);
        static::listen($eventName . ': ' . $class, $listener);
    }

    /**
     * Runs after dispatching a command, you can optionally pass a command class too so it only runs after that command has been dispatched
     *
     * ```php
     * Dispatcher::after(function(CommandInspector $inspector, $result=null){
     * });
     * Dispatcher::after(BootApp::class, function(CommandInspector $inspector, $result=null){
     * });
     * ```
     *
     * @param string|callable $command  Either a listener function or a FQCN string
     * @param null|callable   $listener If you used a FQCN string for $command then this should be a listener function
     *
     * @return void
     */
    public static function after($command, $listener = null)
    {
        $eventName = 'bus.' . self::EVENT_AFTER;
        if ($listener === null) {
            return static::listen($eventName, $command);
        }
        $class = is_string($command) ? $command : get_class($command);
        static::listen($eventName . ': ' . $class, $listener);
    }

}
