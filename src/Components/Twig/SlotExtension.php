<?php

namespace Pyradic\Platform\Components\Twig;

use Pyradic\Platform\Components\Twig\tag\SlotParser;
use Pyradic\Platform\Components\Twig\tag\ComponentParser;
use Pyradic\Platform\Components\Service\ComponentRenderer;

/**
 * Class SlotExtension
 * @package Olveneer\TwigComponentsBundle\Twig
 */
class SlotExtension extends \Twig_Extension
{
    /**
     * @var ComponentRenderer
     */
    private $renderer;

    /**
     * TwigComponentExtension constructor.
     * @param ComponentRenderer $componentRenderer
     */
    public function __construct(ComponentRenderer $componentRenderer)
    {
        $this->renderer = $componentRenderer;
    }

    /**
     * @return array|\Twig_TokenParserInterface[]
     */
    public function getTokenParsers()
    {
        return [new ComponentParser(), new SlotParser()];
    }

    /**
     * @return ComponentRenderer
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @return \Twig_Compiler
     */
    public function createCompiler()
    {
        return new \Twig_Compiler($this->renderer->getEnv());
    }

}
