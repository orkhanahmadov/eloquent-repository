<?php

namespace Orkhanahmadov\EloquentRepository\Repository\Concerns;

use Illuminate\Contracts\Cache\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Orkhanahmadov\EloquentRepository\Repository\Contracts\Cacheable;

/**
 * @property-read string $entity
 * @property-read Builder|Model $modelInstance
 * @property-read Factory $cache
 * @method string cacheKey()
 * @method int cacheTTLValue()
 */
trait SelectsEntity
{
    /**
     * Returns all models.
     *
     * @return Builder[]|Collection
     */
    public function all()
    {
        if ($this instanceof Cacheable) {
            return $this->cache->remember(
                $this->cacheKey() . '.*',
                $this->cacheTTLValue(),
                function () {
                    return $this->get();
                }
            );
        }

        return $this->get();
    }

    /**
     * Returns all models with selected columns.
     *
     * @param mixed $columns
     *
     * @return Builder[]|Collection
     */
    public function get(...$columns)
    {
        $columns = Arr::flatten($columns);

        if (count($columns) === 0) {
            $columns = ['*'];
        }

        return $this->modelInstance->get($columns);
    }

    /**
     * Returns first model.
     *
     * @return Builder|Model|null
     */
    public function first()
    {
        return $this->modelInstance->first();
    }

    /**
     * Finds a model with ID.
     *
     * @param int|string $modelId
     *
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function find($modelId)
    {
        if ($this instanceof Cacheable) {
            $model = $this->cache->remember(
                $this->cacheKey() . '.' . $modelId,
                $this->cacheTTLValue(),
                function () use ($modelId) {
                    return $this->modelInstance->find($modelId);
                }
            );
        } else {
            $model = $this->modelInstance->find($modelId);
        }

        if (! $model) {
            throw (new ModelNotFoundException())->setModel($this->entity, $modelId);
        }

        return $model;
    }

    /**
     * Paginates models.
     *
     * @param int $perPage
     *
     * @return Builder[]|Collection|mixed
     */
    public function paginate(int $perPage)
    {
        return $this->modelInstance->paginate($perPage);
    }

    /**
     * Finds models with "where" condition.
     *
     * @param string|array $column
     * @param mixed $value
     *
     * @return Builder[]|Collection
     */
    public function getWhere($column, $value = null)
    {
        if (is_array($column)) {
            return $this->modelInstance->where($column)->get();
        }

        return $this->modelInstance->where($column, $value)->get();
    }

    /**
     * Finds models with "whereIn" condition.
     *
     * @param string $column
     * @param mixed $values
     *
     * @return Builder[]|Collection
     */
    public function getWhereIn(string $column, $values)
    {
        return $this->modelInstance->whereIn($column, $values)->get();
    }

    /**
     * Finds first model with "where" condition.
     *
     * @param string|array $column
     * @param mixed $value
     *
     * @return Builder|Model|object|null
     */
    public function getWhereFirst($column, $value = null)
    {
        if (is_array($column)) {
            $model = $this->modelInstance->where($column)->first();
        } else {
            $model = $this->modelInstance->where($column, $value)->first();
        }

        if (! $model) {
            throw (new ModelNotFoundException())->setModel($this->entity);
        }

        return $model;
    }

    /**
     * Finds first model with "whereIn" condition.
     *
     * @param string $column
     * @param mixed $values
     *
     * @return Builder|Model|object|null
     */
    public function getWhereInFirst(string $column, $values)
    {
        $model = $this->modelInstance->whereIn($column, $values)->first();

        if (! $model) {
            throw (new ModelNotFoundException())->setModel($this->entity);
        }

        return $model;
    }
}
