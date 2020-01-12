<?php

namespace Pyro\Platform\Ui;

use Anomaly\Streams\Platform\Ui\Table\Component\Row\Row;
use Anomaly\Streams\Platform\Ui\Table\Table;
use Pyro\Platform\Support\Decoder;

class Normalizer
{

    public static function table(Table $table)
    {
        $builder = $table->getHeaders()->first()->getBuilder();

        $filters    = $table->getFilters()->map(Decoder::mapper('ignore', [ 'stream' ]))->toBase()->values();
        $actions    = $table->getActions()->map(Decoder::mapper('ignore', []))->where('parent', null)->toBase()->values();
        $pagination = collect($table->getData()->get('pagination', []))->except([ 'data', 'links' ]);
        $options    = $table->getOptions()->toBase();
        $stream     = collect($table->getStream()->toArrayWithRelations());
        $entries    = $table->getEntries()->map->toArrayWithRelations()->toBase();
        $views      = $table->getViews()->map(Decoder::mapper('ignore', []))->toBase()->values();
        $rows       = $table->getRows()->map(function (Row $row) {
            $data              = Decoder::only([ 'key', 'class' ])->decode($row);
            $data[ 'buttons' ] = $row->getButtons()->map(Decoder::mapper('ignore', [ 'entry' ]))->where('parent', null)->toBase();
            $data[ 'columns' ] = $row->getColumns()->map(Decoder::mapper('ignore', [ 'entry' ]))->toBase();
            return $data;
        })->map('collect')->toBase();

        $headers    = $table->getHeaders()->map(Decoder::mapper('ignore', [ 'builder' ]))->toBase();
        $headers    = collect($builder->getColumns())->map(function($column) use ($headers) {
            $header = $headers->firstWhere('heading', $column['heading']);
            return array_replace($column,$header);
        });
        $headers = $headers->values();

        $data       = collect(compact('filters', 'actions', 'headers', 'pagination', 'options', 'stream', 'entries', 'views', 'rows'));
        return $data;
    }
}
