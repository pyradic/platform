<?php namespace Anomaly\Streams\Platform\Ui\ControlPanel\Component\Button;

use Anomaly\Streams\Platform\Support\Authorizer;
use Anomaly\Streams\Platform\Ui\Button\ButtonFactory;
use Anomaly\Streams\Platform\Ui\ControlPanel\ControlPanelBuilder;

/**
 * Class ButtonBuilder
 *
 * @link    http://pyrocms.com/
 * @author  PyroCMS, Inc. <support@pyrocms.com>
 * @author  Ryan Thompson <ryan@pyrocms.com>
 */
class ButtonBuilder
{

    /**
     * The input reader.
     *
     * @var ButtonInput
     */
    protected $input;

    /**
     * The button factory.
     *
     * @var ButtonFactory
     */
    protected $factory;

    protected $authorizer;

    /**
     * Create a new ButtonBuilder instance.
     *
     * @param ButtonInput   $input
     * @param ButtonFactory $factory
     */
    public function __construct(ButtonInput $input, ButtonFactory $factory, Authorizer $authorizer)
    {
        $this->input   = $input;
        $this->factory = $factory;
        $this->authorizer=$authorizer;
    }

    /**
     * Build the buttons.
     *
     * @param ControlPanelBuilder $builder
     */
    public function build(ControlPanelBuilder $builder)
    {
        $controlPanel = $builder->getControlPanel();

        $this->input->read($builder);

        foreach ($builder->getButtons() as $button) {

            if (!$this->authorizer->authorize(array_get($button, 'permission'))) {
                continue;
            }


            if (($button = $this->factory->make($button)) && $button->isEnabled()) {
                $controlPanel->addButton($button);
            }
        }
    }
}
