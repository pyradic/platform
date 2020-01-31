<?php namespace Anomaly\Streams\Platform\Ui\Table\Component\Action;

use Anomaly\Streams\Platform\Support\Authorizer;
use Anomaly\Streams\Platform\Ui\Table\Component\Action\ActionFactory;
use Anomaly\Streams\Platform\Ui\Table\Component\Action\ActionInput;
use Anomaly\Streams\Platform\Ui\Table\TableBuilder;

class ActionBuilder
{

    /**
     * The action reader.
     *
     * @var ActionInput
     */
    protected $input;

    /**
     * The action factory.
     *
     * @var ActionFactory
     */
    protected $factory;

    /** @var \Anomaly\Streams\Platform\Support\Authorizer  */
    protected $authorizer;

    public function __construct(Authorizer $authorizer,ActionInput $input, ActionFactory $factory)
    {
        $this->authorizer=$authorizer;
        $this->input   = $input;
        $this->factory = $factory;
    }

    /**
     * Build the actions.
     *
     * @param TableBuilder $builder
     */
    public function build(TableBuilder $builder)
    {
        $table = $builder->getTable();

        $this->input->read($builder);

        foreach ($builder->getActions() as $action) {
            if (($permission = array_get($action, 'permission')) && ! $this->authorizer->authorize($permission)) {
                array_set($action, 'enabled', false);
            }

            if (array_get($action, 'enabled', true)) {
                $table->addAction($this->factory->make($action));
            }
        }
    }
}
