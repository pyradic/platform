<?php

namespace Pyro\Platform\Component;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Pyro\Platform\Ui\TreeNode\NodeInterface;
use ArrayAccess;
use JsonSerializable;

/**
 * @mixin Component
 */
interface ComponentInterface extends NodeInterface, Arrayable, Jsonable, ArrayAccess, JsonSerializable
{

}
