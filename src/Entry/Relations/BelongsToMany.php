<?php

namespace Pyro\Platform\Entry\Relations;

class BelongsToMany extends \Illuminate\Database\Eloquent\Relations\BelongsToMany
{
    public function sync($ids, $detaching = true)
    {
        $related  = $this->getRelated();
        $parent   = $this->getParent();
        $relation = $this;
        $this->getParent()->callFireModelEvent('sync', [ $related, $parent, $relation, $ids, $detaching ]);
        $result = parent::sync($ids, $detaching);
        $this->getParent()->callFireModelEvent('synced', [ $related, $parent, $relation, $ids, $detaching, $result ]);
        return $result;
    }
}
