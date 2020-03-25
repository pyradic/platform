<?php

namespace Pyro\Platform\Workflow\Aware;

use Illuminate\Support\Str;

class AwareProvider
{
    protected $map = [];

    public function __construct(array $map)
    {
        $this->map = $map;
    }

    public function setMap(array $map)
    {
        $this->map = $map;
    }

    public function getMap()
    {
        return $this->map;
    }

    public function provide(object $target)
    {
        foreach ($this->map as $interface => $value) {
            if ($target instanceof $interface) {
                $shortName  = last(explode('\\', $interface));
                $methodName = 'set' . Str::removeRight($shortName, 'AwareInterface');
                if (method_exists($target, $methodName)) {
                    $target->{$methodName}($value);
                }
            }
        }
        return $target;
    }
}
