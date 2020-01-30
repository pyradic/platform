<?php

namespace Pyro\Platform;

use Illuminate\Contracts\Container\Container;

class HookDispatchHandler
{
    /** @var \Illuminate\Contracts\Container\Container */
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function handle(HookDispatch $dispatch)
    {
        $instance = new $dispatch->command(...$dispatch->arguments);
        return $this->container->call([ $instance, 'handle' ]);
    }
}
