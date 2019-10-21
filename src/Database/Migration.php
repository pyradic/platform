<?php

namespace Pyro\Platform\Database;

use Closure;

class Migration  extends \Anomaly\Streams\Platform\Database\Migration\Migration
{
    protected $up = [];
    protected $down = [];

    public function addUp(Closure $up)
    {
        $this->up[] = $up;
        return $this;
    }

    public function addDown(Closure $down)
    {
        $this->down[] = $down;
        return $this;
    }

    public function up()
    {
        array_walk($this->up, [ $this, 'visit' ]);
    }

    public function down()
    {
        array_walk($this->down, [ $this, 'visit' ]);
    }

    public function visit(Closure $cb)
    {
        app()->call($cb->bindTo($this));
    }


    public function setNamespace(?string $namespace)
    {
        $this->namespace = $namespace;
        return $this;
    }

    public function setDelete(bool $delete)
    {
        $this->delete = $delete;
        return $this;
    }
}
