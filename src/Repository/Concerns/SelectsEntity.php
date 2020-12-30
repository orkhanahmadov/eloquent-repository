<?php

namespace Orkhanahmadov\EloquentRepository\Repository\Concerns;

use Illuminate\Support\Arr;
use Illuminate\Contracts\Cache\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Orkhanahmadov\EloquentRepository\Repository\Contracts\Cacheable;

/**
 * @property-read Builder|Model $model
 * @property-read Factory $cache
 * @method string cacheKey()
 * @method int cacheTTLValue()
 * @mixin \Orkhanahmadov\EloquentRepository\EloquentRepository
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

        return $this->model->get($columns);
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
                    return $this->model->find($modelId);
                }
            );
        } else {
            $model = $this->model->find($modelId);
        }

        if (! $model) {
            $this->throwModelNotFoundException($modelId);
        }

        return $model;
    }

    /**
     * Paginates models.
     *
     * @param int|null $perPage
     *
     * @return Builder[]|Collection|mixed
     */
    public function paginate(?int $perPage = null)
    {
        return $this->model->paginate($perPage);
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
            return $this->model->where($column)->get();
        }

        return $this->model->where($column, $value)->get();
    }

    /**
     * Determine if any rows exist for the current query.
     *
     * @param $column
     * @param null $value
     * @return bool
     */
    public function exists($column, $value = null)
    {
        if (is_array($column)) {
            return $this->model->where($column)->exists();
        }

        return $this->model->where($column, $value)->exists();
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
        return $this->model->whereIn($column, $values)->get();
    }

    /**
     * Finds first model with "where" condition.
     *
     * @param string|array $column
     * @param mixed $value
     * @param bool $failIfNotFound
     * @return Builder|Model|object|null
     */
    public function getWhereFirst($column, $value = null, $failIfNotFound = false)
    {
        if (is_array($column)) {
            $model = $this->model->where($column)->first();
        } else {
            $model = $this->model->where($column, $value)->first();
        }

        if (! $model && $failIfNotFound) {
            $this->throwModelNotFoundException();
        }

        return $model;
    }

    /**
     * Finds first model with "whereIn" condition.
     *
     * @param string $column
     * @param mixed $values
     * @param bool $failIfNotFound
     * @return Builder|Model|object|null
     */
    public function getWhereInFirst(string $column, $values, $failIfNotFound = false)
    {
        $model = $this->model->whereIn($column, $values)->first();

        if (! $model && $failIfNotFound) {
            $this->throwModelNotFoundException();
        }

        return $model;
    }
}
