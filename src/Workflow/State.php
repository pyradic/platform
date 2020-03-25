<?php

namespace Pyro\Platform\Workflow;

use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;
use Illuminate\Support\Arr;

class State
{
    /** @var \Pyro\Platform\Workflow\Workflow */
    public $workflow;

    /** @var EntryInterface */
    public $subject;

    /** @var string */
    public $place;

    /** @var \Pyro\Platform\Workflow\StateStore */
    public $store;

    /** @var array */
    public $metadata = [];

    public function set($key, $value)
    {
        data_set($this->metadata, $key, $value);
        return $this;
    }

    public function get($key, $default = null)
    {
        return data_get($this->metadata, $key, $default);
    }

    public function has($keys)
    {
        return Arr::has($this->metadata, $keys);
    }

    public function save()
    {
        $this->store->save($this);
        return $this;
    }

    public function getPlace()
    {
        return $this->workflow->places[$this->place];
    }
}
