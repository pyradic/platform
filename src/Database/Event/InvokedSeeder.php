<?php

namespace Pyro\Platform\Database\Event;

use Illuminate\Database\Seeder;

class InvokedSeeder
{
    /** @var \Illuminate\Database\Seeder */
    protected $seeder;

    /** @var string */
    protected $class;

    /** @var array */
    protected $result;

    /**
     * @param \Illuminate\Database\Seeder $seeder
     */
    public function __construct(Seeder $seeder, $result = null)
    {
        $this->seeder = $seeder;
        $this->class = get_class($seeder);
    }

    public function getSeeder()
    {
        return $this->seeder;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function getResult()
    {
        return $this->result;
    }



}
