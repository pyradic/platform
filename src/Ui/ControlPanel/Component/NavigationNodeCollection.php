<?php

namespace Pyro\Platform\Ui\ControlPanel\Component;

use Pyro\Platform\Ui\TreeNode\NodeCollection;

class NavigationNodeCollection extends NodeCollection
{
    public function type($type)
    {
        return $this->filter(function(NavigationNode $node) use ($type){
            return $node->getType() === $type;
        });
    }

    public function buttons()
    {
        return $this->type('button');
    }

    public function sections()
    {
        return $this->type('section');
    }

    public function navigations()
    {
        return $this->type('navigation');
    }
}
