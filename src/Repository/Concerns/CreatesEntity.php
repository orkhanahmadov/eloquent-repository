<?php

namespace Orkhanahmadov\EloquentRepository\Repository\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read Builder|Model $modelInstance
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
            $model = $this->modelInstance->{$this->relation}()->create($properties);

            $this->relation = null;

            return $model;
        }

        return $this->modelInstance->create($properties);
    }
}
