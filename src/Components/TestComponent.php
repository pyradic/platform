<?php

namespace Pyradic\Platform\Components;

use Pyradic\Platform\Components\Component\TwigComponent;

class TestComponent extends TwigComponent
{
    public function getName()
    {
        return 'test';
    }

    public function getTemplateName()
    {
        return $this->getName() . '.twig';
    }
}
