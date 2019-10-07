<?php

namespace Orkhanahmadov\EloquentRepository\Repository\Concerns;

use Illuminate\Contracts\Cache\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Orkhanahmadov\EloquentRepository\Repository\Contracts\Cacheable;

/**
 * @property-read Builder|Model $entity
 * @property-read Factory $cache
 * @method Builder|Model find(int $modelId)
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
    public function update($model, $properties)
    {
        if ($this instanceof Cacheable) {
            $this->invalidateCache($model);
        }

        $model->fill($properties)->save();

        return $model->refresh();
    }
}
