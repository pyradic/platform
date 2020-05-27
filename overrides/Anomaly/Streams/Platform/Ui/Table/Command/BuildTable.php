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
        Hooks::dispatch([ SetTableModel::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ SetTableStream::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ SetDefaultParameters::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ SetRepository::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ BuildViews::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ SetActiveView::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ SetTableOptions::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ SetDefaultOptions::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ SaveTableState::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ AuthorizeTable::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ BuildFilters::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ SetActiveFilters::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ BuildActions::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ SetActiveAction::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ BuildHeaders::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ EagerLoadRelations::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ GetTableEntries::class, get_class($this->builder) ],[ $this->builder ]);
        Hooks::dispatch([ BuildRows::class, get_class($this->builder) ],[ $this->builder ]);
        $classes = [SetTableModel::class,SetTableStream::class,SetDefaultParameters::class,SetRepository::class,BuildViews::class,SetActiveView::class,SetTableOptions::class,SetDefaultOptions::class,SaveTableState::class,AuthorizeTable::class,BuildFilters::class,SetActiveFilters::class,BuildActions::class,SetActiveAction::class,BuildHeaders::class,EagerLoadRelations::class,GetTableEntries::class,BuildRows::class, ];
    }
}
