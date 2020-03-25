<?php

namespace Pyro\Platform\Workflow;

use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;
use Illuminate\Contracts\Cache\Repository;

class StateStore
{
    /** @var \Pyro\Platform\Workflow\Workflow */
    public $workflow;

    /** @var Repository */
    public $store;

    public function __construct(Workflow $workflow, Repository $store)
    {
        $this->workflow = $workflow;
        $this->store    = $store;
    }

    public function getKey(EntryInterface $subject)
    {
        return "workflow.{$subject->getStreamNamespace()}.{$subject->getStreamSlug()}::{$this->workflow->slug}:{$subject->getId()}";
    }

    public function has(EntryInterface $subject)
    {
        return $this->store->has($this->getKey($subject));
    }

    public function load(EntryInterface $subject)
    {
        $state           = $this->store->get($this->getKey($subject));
        $state->subject  = $subject;
        $state->store    = $this;
        $state->workflow = $this->workflow;
        return $state;
    }

    public function save(State $state)
    {
        $clone           = clone $state;
        $clone->subject  = null;
        $clone->workflow = null;
        $clone->store    = null;
        $this->store->set($this->getKey($state->subject), $clone);
        return $this;
    }

    public function create(EntryInterface $subject)
    {
        $subject->getKeyName();
        $state        = new State;
        $state->place = app()->call($this->workflow->resolve_place, compact('subject'));
        $this->store->set($this->getKey($subject), $state, 60);
        $state->store    = $this;
        $state->subject  = $subject;
        $state->workflow = $this->workflow;
        return $state;
    }

    public function getOrCreate(EntryInterface $subject)
    {
        return $this->has($subject) ? $this->load($subject) : $this->create($subject);
    }
}
