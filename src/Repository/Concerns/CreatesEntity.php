<?php

namespace Orkhanahmadov\EloquentRepository\Repository\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Orkhanahmadov\EloquentRepository\EloquentRepository;

/**
 * @property-read Builder|Model $model
 * @mixin EloquentRepository
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
        return $this->model
            ->create($properties);
    }
}
