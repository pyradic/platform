<?php

namespace Pyro\Platform\Support;

use Anomaly\Streams\Platform\Support\Presenter;

class Hydrator extends \Anomaly\Streams\Platform\Support\Hydrator
{
    public function dehydrate($object)
    {
        if ($object instanceof Presenter) {
            $object->getObject();
        }
        return $object;
    }
}
