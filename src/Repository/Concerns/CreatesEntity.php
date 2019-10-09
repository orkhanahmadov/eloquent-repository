<?php

namespace Orkhanahmadov\EloquentRepository\Repository\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read Builder|Model $resolvedEntity
 * @property-read string $relation
 */
trait CreatesEntity
{
    /**
     * Creates model.
     *
     * @param mixed $properties
     *
     * @return Builder|Model
     */
    public function create($properties)
    {
        if ($this->relation) {
            $model = $this->resolvedEntity->{$this->relation}()->create($properties);

            $this->relation = null;

            return $model;
        }

        return $this->resolvedEntity->create($properties);
    }
}
