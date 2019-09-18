<?php

namespace Pyradic\Platform\Components\Twig\tag;

use Pyradic\Platform\Components\Exception\SlotSyntaxException;

/**
 * Class SlotNode
 * @package Olveneer\TwigComponentsBundle\Twig\tag\component
 */
class SlotNode extends \Twig_Node implements \Twig_NodeOutputInterface
{
    /**
     * SlotNode constructor.
     * @param $params
     * @param int $lineno
     * @param null $tag
     */
    public function __construct($params, $lineno = 0, $tag = null)
    {
        parent::__construct(['params' => $params], [], $lineno, $tag);
    }

    /**
     * @param \Twig_Compiler $compiler
     * @throws SlotSyntaxException
     */
    public function compile(\Twig_Compiler $compiler)
    {

        $params = $this->getNode('params');

        $compiler
            ->addDebugInfo($this);

        $compiler->write('$renderer = app()->make(')
            ->string('crvs.components.renderer')
            ->raw(');')->raw(PHP_EOL);

        $compiler->write('$compiler = app()->make(')
            ->string('twig.compiler')
            ->raw(');')->raw(PHP_EOL);


        /** @var \Twig_Node[] $nodes */
        $nodes = $params->nodes;

        if (!$nodes[1] instanceof \Twig_Node_Expression_Name) {
            throw new SlotSyntaxException("Use unquoted strings for the {% slot %} tag.");
        }

        $name = $nodes[1]->getAttribute('name');

        $compiler->write('$exposed = [];')->raw(PHP_EOL);

        if (isset($nodes[2])) {
            $exposes = $nodes[2]->getAttribute('name');
            if ($exposes === 'expose') {
                if (isset($nodes[3]) && $nodes[3] instanceof  \Twig_Node_Expression_Array) {
                    $compiler
                        ->write('$exposed = ')
                        ->subcompile($nodes[3])->raw(';')->raw(PHP_EOL);
                } else {
                    throw new SlotSyntaxException("Expose expects an object{} of values to expose.");
                }
            } else {
                throw new SlotSyntaxException("The {% slot %} tag expects 'expose', '$exposes' was given instead");
            }
        }

        $compiler->write('$oldContext = $context; ')->raw(PHP_EOL)
            ->write('$parentContext = $renderer->getContext();')->raw(PHP_EOL)
            ->write('$context = array_merge($parentContext, $exposed);')->raw(PHP_EOL);

        $compiler
            ->write('$isSlotted = $renderer->hasSlot(')
            ->string($name)
            ->raw(');')
            ->raw(PHP_EOL);

        $compiler
            ->write('if ($isSlotted) {')->raw(PHP_EOL)
            ->indent()
            ->write('$nodes = $renderer->getSlot(')
            ->string($name)
            ->raw(');')->raw(PHP_EOL)
            ->write('$nodes->compile($compiler);')->raw(PHP_EOL)
            ->write('eval($compiler->getSource());')->raw(PHP_EOL)
            ->outdent()
            ->write('} else {')->raw(PHP_EOL)
            ->indent()
                ->subCompile($nodes[0])
            ->outdent()
            ->raw('}')->raw(PHP_EOL)
            ->write('$context = $oldContext;')->raw(PHP_EOL);
    }
}
