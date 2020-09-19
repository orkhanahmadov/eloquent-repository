<?php

namespace Orkhanahmadov\EloquentRepository\Repository\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Orkhanahmadov\EloquentRepository\EloquentRepository;
use Orkhanahmadov\EloquentRepository\Repository\Contracts\Cacheable;

/**
 * @method Builder|Model find($modelId)
 * @method void invalidateCache($model)
 * @mixin EloquentRepository
 */
trait UpdatesEntity
{
    /**
     * Finds a model with ID and updates it with given properties.
     *
     * @param int|string $modelId
     * @param mixed $properties
     *
     * @return Builder|Model
     */
    public function findAndUpdate($modelId, $properties)
    {
        $model = $this->find($modelId);

        return $this->update($model, $properties);
    }

    /**
     * Updates a model given properties.
     *
     * @param Model $model
     * @param mixed $properties
     *
     * @return Builder|Model
     */
    public function update(Model $model, $properties)
    {
        if ($this instanceof Cacheable) {
            $this->invalidateCache($model);
        }

        $model->fill($properties)->save();

        return $model->refresh();
    }
}
