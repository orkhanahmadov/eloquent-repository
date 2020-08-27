<?php

namespace Orkhanahmadov\EloquentRepository\Repository\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property-read Builder|Model $model
 * @mixin \Orkhanahmadov\EloquentRepository\EloquentRepository
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
        return $this->model->create($properties);
    }
}
