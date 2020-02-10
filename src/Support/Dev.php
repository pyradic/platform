<?php

namespace Pyro\Platform\Support;

use Illuminate\Contracts\Foundation\Application;

class Dev
{
    /** @var \Illuminate\Contracts\Foundation\Application */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function addEvent($event, $eventData = null, $time = null, $data = null)
    {
        if ($this->app->bound('clockwork')) {
            $this->app->clockwork->addEvent($event, $eventData, $time, $data);
        }
        return $this;
    }

    public function startEvent($name, $desc, $time = null)
    {
        if ($this->app->bound('clockwork')) {
            $this->app->clockwork->startEvent($name, $desc, $time);
        }
        return $this;
    }

    public function endEvent($name)
    {
        if ($this->app->bound('clockwork')) {
            $this->app->clockwork->endEvent($name);
        }
        return $this;
    }
}
