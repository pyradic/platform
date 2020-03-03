<?php

namespace Pyro\Platform\Ui\ControlPanel\Component;

use Pyro\Platform\Ui\TreeNode\NodeCollection;

class NavigationNodeCollection extends NodeCollection
{

    /**
     * @return \Pyro\Platform\Ui\ControlPanel\Component\NavigationNodeCollection|\Pyro\Platform\Ui\ControlPanel\Component\NavigationNode[]
     */
    public function type($type)
    {
        return $this->filter(function(NavigationNode $node) use ($type){
            return $node->getType() === $type;
        });
    }

    /**
     * @return \Pyro\Platform\Ui\ControlPanel\Component\NavigationNodeCollection|\Pyro\Platform\Ui\ControlPanel\Component\NavigationNode[]
     */
    public function buttons()
    {
        return $this->type('button');
    }

    /**
     * @return \Pyro\Platform\Ui\ControlPanel\Component\NavigationNodeCollection|\Pyro\Platform\Ui\ControlPanel\Component\NavigationNode[]
     */
    public function sections()
    {
        return $this->type('section');
    }


    /**
     * @return \Pyro\Platform\Ui\ControlPanel\Component\NavigationNodeCollection|\Pyro\Platform\Ui\ControlPanel\Component\NavigationNode[]
     */
    public function navigations()
    {
        return $this->type('navigation');
    }
}
