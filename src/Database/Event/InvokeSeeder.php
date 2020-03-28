<?php

namespace Pyro\Platform\Database\Event;

use Illuminate\Database\Seeder;

class InvokeSeeder
{
    /** @var \Illuminate\Database\Seeder */
    protected $seeder;

    /** @var string */
    protected $class;

    /**
     * @param \Illuminate\Database\Seeder $seeder
     */
    public function __construct(Seeder $seeder)
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
}
