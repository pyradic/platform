<?php namespace Anomaly\Streams\Platform\Ui\Form\Command;

use Anomaly\Streams\Platform\Ui\Form\Component\Action\Command\BuildActions;
use Anomaly\Streams\Platform\Ui\Form\Component\Action\Command\SetActiveAction;
use Anomaly\Streams\Platform\Ui\Form\Component\Button\Command\BuildButtons;
use Anomaly\Streams\Platform\Ui\Form\Component\Field\Command\BuildFields;
use Anomaly\Streams\Platform\Ui\Form\Component\Section\Command\BuildSections;
use Anomaly\Streams\Platform\Ui\Form\Event\FormWasBuilt;
use Anomaly\Streams\Platform\Ui\Form\FormBuilder;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Pyro\Platform\Hooks;

/**
 * Class BuildForm
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class BuildForm
{

    use DispatchesJobs;

    /**
     * The form builder.
     *
     * @var FormBuilder
     */
    protected $builder;

    /**
     * Create a new BuildFormColumnsCommand instance.
     *
     * @param FormBuilder $builder
     */
    public function __construct(FormBuilder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * Handle the command.
     */
    public function handle()
    {

        /*
         * Setup some objects and options using
         * provided input or sensible defaults.
         */
        Hooks::dispatch([ AddAssets::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ SetFormModel::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ SetFormStream::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ SetRepository::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ SetFormEntry::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ SetFormVersion::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ SetDefaultParameters::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ SetFormOptions::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ SetDefaultOptions::class, get_class($this->builder) ],[ $this->builder ]);

        /*
         * Load anything we need that might be flashed.
         */
        Hooks::dispatch([ LoadFormErrors::class, get_class($this->builder) ],[ $this->builder ]);

        /*
         * Before we go any further, authorize the request.
         */
        Hooks::dispatch([ AuthorizeForm::class, get_class($this->builder) ],[ $this->builder ]);

        /*
         * Lock form model.
         */
        Hooks::dispatch([ LockFormModel::class, get_class($this->builder) ],[ $this->builder ]);

        /*
         * Build form fields.
         */
        Hooks::dispatch([ BuildFields::class, get_class($this->builder) ],[ $this->builder ]);

        /*
         * Build form sections.
         */
        Hooks::dispatch([ BuildSections::class, get_class($this->builder) ],[ $this->builder ]);

        /*
         * Build form actions and flag active.
         */
        Hooks::dispatch([ BuildActions::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ SetActiveAction::class, get_class($this->builder) ],[ $this->builder ]);

        /*
         * Build form buttons.
         */
        Hooks::dispatch([ BuildButtons::class, get_class($this->builder) ],[ $this->builder ]);

        event(new FormWasBuilt($this->builder));
    }
}
