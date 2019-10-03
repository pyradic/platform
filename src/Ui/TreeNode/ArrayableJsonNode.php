<?php


namespace Pyradic\Platform\Ui\TreeNode;


trait ArrayableJsonNode
{

    public function toArray()
    {
        return $this->getChildren()->toArray();
    }

    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
