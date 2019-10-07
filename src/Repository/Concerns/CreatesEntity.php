<?php

namespace Orkhanahmadov\EloquentRepository\Repository\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property-read Builder|Model $model
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
            return $this->model->{$this->relation}()->create($properties);
        }

        return $this->model->create($properties);
    }
}
