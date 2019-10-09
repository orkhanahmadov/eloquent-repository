<?php

namespace Orkhanahmadov\EloquentRepository\Repository\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @property-read string $entity
 * @property-read Builder|Model $resolvedEntity
 * @property-read Model $model
 * @method Builder|Model find(int $modelId)
 * @method void invalidateCache()
 */
trait DeletesEntity
{
//    /**
//     * Finds a model with ID and deletes it.
//     *
//     * @param int|string $modelId
//     *
//     * @return bool
//     *
//     * @throws \Exception
//     */
//    public function findAndDelete($modelId): bool
//    {
//        $model = $this->find($modelId);
//
//        return $this->delete($model);
//    }

    /**
     * Deletes a model.
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function delete(): bool
    {
        $result = $this->model->delete();

        $this->model = null;

        return $result;
    }

//    /**
//     * Finds a soft deleted model with given ID and restores it.
//     *
//     * @param int|string $modelId
//     *
//     * @return bool|null
//     */
//    public function findAndRestore($modelId)
//    {
//        $model = $this->findFromTrashed($modelId);
//
//        return $this->restore($model);
//    }

    /**
     * Finds a soft deleted model with given ID.
     *
     * @param int|string $modelId
     *
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function findFromTrashed($modelId)
    {
        if (! method_exists($this->entity, 'restore')) {
            throw new \BadMethodCallException('Model is not using "soft delete" feature.');
        }

        $model = $this->resolvedEntity->onlyTrashed()->find($modelId);

        if (! $model) {
            throw (new ModelNotFoundException())->setModel($this->entity, $modelId);
        }

        return $model;
    }

    /**
     * Restores soft deleted model.
     *
     * @return bool|null
     */
    public function restore()
    {
        if (! method_exists($this->entity, 'restore')) {
            throw new \BadMethodCallException($this->entity . ' is not using "soft delete" feature.');
        }

        $result = $this->model->restore();

        $this->model = null;

        return $result;
    }
}
