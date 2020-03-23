<?php

namespace Pyro\Platform\Workflow;

use Laradic\Support\Dot;

class State extends Dot
{

    public function __construct()
    {
        parent::__construct();
    }

    public function save()
    {
        session()->put($this->getKey(), $this->toArray());
        return $this;
    }

    public function load()
    {
        $this->items = session()->get($this->getKey(), []);
        return $this;
    }

    public function destroy()
    {
        session()->remove($this->getKey());
        return $this;
    }

    public function getCurrentStep()
    {
        return $this->get('current_step');
    }
    public function setCurrentStep($step)
    {
        if($step instanceof StepInterface){
            $step->getSlug();
        }
        return $this->set('current_step', (string) $step);
    }

    /** @var string */
    protected $key;

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

}
