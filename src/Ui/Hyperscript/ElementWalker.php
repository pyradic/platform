<?php

namespace Pyro\Platform\Ui\Hyperscript;

class ElementWalker
{
    public function walk(\Pyro\Platform\Ui\Hyperscript\Element $element, \Pyro\Platform\Ui\Hyperscript\ElementVisitor $visitor)
    {
        $visitor->visit($element);
        foreach ($element->children as $child) {
            $this->walk($child, $visitor);
        }
        $visitor->leave($element);
    }
}
