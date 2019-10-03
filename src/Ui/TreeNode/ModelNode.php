<?php

namespace Pyradic\Platform\Ui\TreeNode;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

class ModelNode extends ValueNode implements Arrayable, ArrayAccess, Jsonable, JsonSerializable
{
    use ArrayableJsonNode {
        toArray as __toArray;
    }
    protected $collectionClass = ModelNodeCollection::class;

    protected $hidden = [ 'id', 'created_at', 'created_by_id', 'updated_at', 'updated_by_id', 'deleted_at', 'entry_type' ];

    public function __construct($value)
    {
        $this->setValue($value);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel()
    {
        return $this->getValue();
    }

    public function toArray()
    {
        $model  = $this->getModel();
        $hidden = $model->getHidden();
        $model->makeHidden($this->hidden);
        $data = $model->toArray();
        $model->setHidden($hidden);
        $data[ 'children' ] = $this->getChildren()->toArray();


        return $data;
    }

    public function __get($name)
    {
        return $this->getValue()->getAttribute($name);
    }

    public function __set($name, $value)
    {
        return $this->getValue()->setAttribute($name, $value);
    }

    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
        return $this;
    }

    protected function isHidden($key)
    {
        return in_array($key, $this->hidden, true);
    }
}
