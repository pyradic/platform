<?php

namespace Pyro\Platform\Ui\ControlPanel\Component;

use Anomaly\Streams\Platform\Ui\ControlPanel\Component\Button\Guesser\EnabledGuesser;
use Anomaly\Streams\Platform\Ui\ControlPanel\ControlPanelBuilder;

class ButtonEnabledGuesser extends EnabledGuesser
{

    /**
     * Guess the enabled property.
     *
     * @param ControlPanelBuilder $builder
     */
    public function guess(ControlPanelBuilder $builder)
    {
        $buttons = $builder->getButtons();

        foreach ($buttons as &$button) {

            if (!isset($button['enabled']) || is_bool($button['enabled'])) {
                continue;
            }

            /**
             * This is handy for looking at query string input
             * and toggling buttons on and off if there is a value.
             */
            if (is_string($button['enabled']) && is_numeric($button['enabled'])) {
                $button['enabled'] = true;
            }

        }

        $builder->setButtons($buttons);
    }
}
