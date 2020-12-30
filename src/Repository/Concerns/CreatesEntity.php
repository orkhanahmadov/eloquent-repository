<?php

namespace Orkhanahmadov\EloquentRepository\Repository\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Orkhanahmadov\EloquentRepository\Repository\Contracts\Cacheable;

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
        $model = $this->model->create($properties);

        if ($this instanceof Cacheable) {
            $this->invalidateCache($model);
        }

        return $model;
    }
}
