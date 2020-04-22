<?php

namespace Pyro\Platform\Ui\Table;

use Anomaly\Streams\Platform\Ui\Table\Component\Column\ColumnCollection;
use Anomaly\Streams\Platform\Ui\Table\TableBuilder;
use Illuminate\Support\Str;

class ColumnBuilder extends \Anomaly\Streams\Platform\Ui\Table\Component\Column\ColumnBuilder
{

    public function build(TableBuilder $builder, $entry)
    {
        $table = $builder->getTable();

        $columns = new ColumnCollection();

        $this->input->read($builder);

        foreach ($builder->getColumns() as $column) {
            array_set($column, 'entry', $entry);
            $column = $this->evaluator->evaluate($column, compact('entry', 'table'));

            foreach ($column as $key => &$value) {
                if (starts_with($key, ':')) {
                    $column[ Str::removeLeft($key, ':') ] = \BladeString::compile($value, compact('entry', 'table', 'builder'));
                    unset($column[$key]);
                } elseif (starts_with($key, '!')) {
                    $column[ Str::removeLeft($key, '!') ] = \ExpressionParser::parse($value,  compact('entry', 'table', 'builder'));
                    unset($column[$key]);
                }
            }

            $column[ 'value' ] = $this->value->make($table, $column, $entry);

            $columns->push($this->factory->make($column));
        }

        return $columns;
    }
}
