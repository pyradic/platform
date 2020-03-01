<?php

namespace Pyro\Platform\Ui\ControlPanel\Component;

use Anomaly\Streams\Platform\Ui\ControlPanel\ControlPanelBuilder;

/**
 * Class ButtonBuilder
 *
 * @link    http://pyrocms.com/
 * @author  PyroCMS, Inc. <support@pyrocms.com>
 * @author  Ryan Thompson <ryan@pyrocms.com>
 */
class ButtonBuilder extends \Anomaly\Streams\Platform\Ui\ControlPanel\Component\Button\ButtonBuilder
{

    /**
     * Build the buttons.
     *
     * @param ControlPanelBuilder $builder
     */
    public function build(ControlPanelBuilder $builder)
    {
        $controlPanel = $builder->getControlPanel();

        $this->input->read($builder);

        foreach ($builder->getButtons() as $slug => $button) {

            if ( ! $this->authorizer->authorize(array_get($button, 'permission'))) {
                continue;
            }
            $button[ 'button' ] = Button::class;
            $button[ 'slug' ]   = $button[ 'slug' ]?? $slug;

            if (($button = $this->factory->make($button)) && $button->isEnabled()) {
                $controlPanel->addButton($button);
            }
        }
    }
}
