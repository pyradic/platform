<?php

namespace Anomaly\Streams\Platform\Ui\Table\Command;

use Anomaly\Streams\Platform\Ui\Table\Component\Action\Command\BuildActions;
use Anomaly\Streams\Platform\Ui\Table\Component\Action\Command\SetActiveAction;
use Anomaly\Streams\Platform\Ui\Table\Component\Filter\Command\BuildFilters;
use Anomaly\Streams\Platform\Ui\Table\Component\Filter\Command\SetActiveFilters;
use Anomaly\Streams\Platform\Ui\Table\Component\Header\Command\BuildHeaders;
use Anomaly\Streams\Platform\Ui\Table\Component\Row\Command\BuildRows;
use Anomaly\Streams\Platform\Ui\Table\Component\View\Command\BuildViews;
use Anomaly\Streams\Platform\Ui\Table\Component\View\Command\SetActiveView;
use Anomaly\Streams\Platform\Ui\Table\TableBuilder;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Pyro\Platform\Hooks;

class BuildTable
{
    use DispatchesJobs;

    /**
     * The table builder.
     *
     * @var TableBuilder
     */
    protected $builder;

    /**
     * Create a new BuildTableColumnsCommand instance.
     *
     * @param TableBuilder $builder
     */
    public function __construct(TableBuilder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * Handle the command.
     */
    public function handle()
    {
        /*
         * Resolve and set the table model and stream.
         */
        Hooks::dispatch([ SetTableModel::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ SetTableStream::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ SetDefaultParameters::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ SetRepository::class, get_class($this->builder) ],[ $this->builder ]);

        /*
         * Build table views and mark active.
         */
        Hooks::dispatch([ BuildViews::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ SetActiveView::class, get_class($this->builder) ],[ $this->builder ]);

        /**
         * Set the table options going forward.
         */
        Hooks::dispatch([ SetTableOptions::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ SetDefaultOptions::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ SaveTableState::class, get_class($this->builder) ],[ $this->builder ]);

        /*
         * Before we go any further, authorize the request.
         */
        Hooks::dispatch([ AuthorizeTable::class, get_class($this->builder) ],[ $this->builder ]);

        /*
         * Build table filters and flag active.
         */
        Hooks::dispatch([ BuildFilters::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ SetActiveFilters::class, get_class($this->builder) ],[ $this->builder ]);

        /*
         * Build table actions and flag active.
         */
        Hooks::dispatch([ BuildActions::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ SetActiveAction::class, get_class($this->builder) ],[ $this->builder ]);

        /*
         * Build table headers.
         */
        Hooks::dispatch([ BuildHeaders::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ EagerLoadRelations::class, get_class($this->builder) ],[ $this->builder ]);

        /*
         * Get table entries.
         */
        Hooks::dispatch([ GetTableEntries::class, get_class($this->builder) ],[ $this->builder ]);

        /*
         * Lastly table rows.
         */
        Hooks::dispatch([ BuildRows::class, get_class($this->builder) ],[ $this->builder ]);
    }
}
