<?php

namespace Pyro\Platform\Workflow\Concerns;

trait HasItem
{

    /** @var mixed */
    protected $item;

    /** @var string */
    protected $itemId;

    public function getItem()
    {
        return $this->item;
    }

    public function setItem($item, $itemId = null)
    {
        $this->item = $item;
        if ($itemId !== null) {
            $this->itemId = $itemId;
        }
        return $this;
    }

    public function getItemId()
    {
        return $this->itemId;
    }

    public function setItemId($itemId)
    {
        $this->itemId = $itemId;
        return $this;
    }
}
