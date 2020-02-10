<?php

namespace Pyro\Platform\Ui\Hyperscript;

interface ElementVisitor
{
    public function visit(Element $element);

    public function leave(Element $element);
}
