<?php namespace Pyro\Platform\Ui\ControlPanel\Component;

use Anomaly\Streams\Platform\Ui\ControlPanel\ControlPanelBuilder;
use Pyro\Platform\Ui\Input;

/**
 * Class SectionInput
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class SectionInput extends \Anomaly\Streams\Platform\Ui\ControlPanel\Component\Section\SectionInput
{
    /**
     * Read the section input and process it
     * before building the objects.
     *
     * @param ControlPanelBuilder $builder
     */
    public function read(ControlPanelBuilder $builder)
    {
        $this->resolver->resolve($builder);
        $this->evaluator->evaluate($builder);

        $sections = $builder->getSections();
        $builder->setSections(Input::expression($sections, compact('builder')));

        $this->normalizer->normalize($builder);
        $this->guesser->guess($builder);
        $this->evaluator->evaluate($builder);
        $this->parser->parse($builder);
    }
}
