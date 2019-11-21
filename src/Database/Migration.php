<?php

namespace Pyro\Platform\Database;

use Closure;
use Illuminate\Support\Arr;

/**
 * A class to create anonymous migrations at run-time.
 *
 * @example
 * ```
 * $migration = (new Migration())
 *  ->addUp(function(){
 *    Schema::create('sometable, function (Blueprint $table) {
 *      $table->bigIncrements('id');
 *    });
 *  })
 *  ->addDown(function(){
 *  });
 * ```
 */
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
        $this->visit($this->down);
    }

    public function down()
    {
        $this->visit($this->down);
    }

    /**
     * @param Closure|Closure[] $cbs
     */
    public function visit($cbs)
    {
        foreach(Arr::wrap($cbs) as $cb) {
            app()->call($cb->bindTo($this));
        }
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
