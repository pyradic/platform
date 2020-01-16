<?php

namespace Pyro\Platform\Entry\Relations;

class MorphTo extends \Illuminate\Database\Eloquent\Relations\MorphTo
{

    public function associate($model)
    {
        $this->getParent()->callFireModelEvent('associate');
        $res = parent::associate($model);
        $this->getParent()->callFireModelEvent('associated');
        return $res;
    }

    public function dissociate()
    {
        $this->getParent()->callFireModelEvent('dissociate');
        $res = parent::dissociate();
        $this->getParent()->callFireModelEvent('dissociate');
        return $res;
    }

}
