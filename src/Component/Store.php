<?php

namespace Pyro\Platform\Component;

use Laradic\Support\Dot;

class Store extends Dot
{
    /** @var static */
    protected static $instance;

    protected function __construct()
    {
        if(static::$instance !== null){
            throw new \RuntimeException('Cannot create another instance of Store');
        }
        parent::__construct([]);
    }

    public function getInstance()
    {
        if(static::$instance === null){
            static::$instance = new static();
        }
        return static::$instance;
    }
}
