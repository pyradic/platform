<?php

namespace Pyro\Platform\Entry;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Pyro\ActivityLogModule\Activity\Traits\CausesActivity;
use Pyro\ActivityLogModule\Activity\Traits\LogsActivity;
use Pyro\Platform\Entry\Relations\BelongsTo;
use Pyro\Platform\Entry\Relations\BelongsToMany;
use Pyro\Platform\Entry\Relations\MorphTo;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class EntryModel extends \Anomaly\Streams\Platform\Entry\EntryModel
{
    use LogsActivity;
    use CausesActivity;
    use HasRelationships;

    public function __construct(array $attributes = [])
    {
        array_push($this->observables, ...[
            'sync',
            'synced',
            'attach',
            'attached',
            'attached',
            'detach',
            'detached',
            'associate',
            'associated',
            'dissociate',
            'dissociated',
        ]);
        parent::__construct($attributes);
    }

    /**
     * Return the creator relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function created_by()
    {
        return $this->createdBy();
    }

    /**
     * Return the updater relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updated_by()
    {
        return $this->updatedBy();
    }

    protected function newMorphTo(Builder $query, Model $parent, $foreignKey, $ownerKey, $type, $relation)
    {
        return new MorphTo($query, $parent, $foreignKey, $ownerKey, $type, $relation);
    }

    protected function newBelongsTo(Builder $query, Model $child, $foreignKey, $ownerKey, $relation)
    {
        return new BelongsTo($query, $child, $foreignKey, $ownerKey, $relation);
    }

    protected function newBelongsToMany(Builder $query, Model $parent, $table, $foreignPivotKey, $relatedPivotKey, $parentKey, $relatedKey, $relationName = null)
    {
        return new BelongsToMany($query, $parent, $table, $foreignPivotKey, $relatedPivotKey, $parentKey, $relatedKey, $relationName);
    }

    public function callClosureWithInstance(Closure $closure)
    {
        return app()->call($closure->bindTo($this));
    }

    public function callFireModelEvent($event, array $extraParams = [], $halt = true)
    {
        $method = $halt ? 'until' : 'dispatch';

        $result = $this->filterModelEventResults(
            $this->fireCustomModelEvent($event, $method)
        );

        if ($result === false) {
            return false;
        }

        return ! empty($result) ? $result : static::$dispatcher->{$method}(
            "eloquent.{$event}: " . static::class, array_merge([ $this ], $extraParams)
        );
    }
}
