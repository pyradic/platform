<?php

namespace Pyradic\Platform\Components;

use Anomaly\Streams\Platform\Addon\Plugin\Plugin;
use Pyradic\Platform\Components\Twig\tag\SlotParser;
use Pyradic\Platform\Components\Twig\tag\ComponentParser;

class ComponentsPlugin extends Plugin
{
    /**
     * @return array|\Twig_TokenParserInterface[]
     */
    public function getTokenParsers()
    {
        return [new ComponentParser(), new SlotParser()];
    }

}
