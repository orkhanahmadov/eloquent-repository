<?php

namespace Innoscripta\EloquentRepository\Repository;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Innoscripta\EloquentRepository\Repository\Contracts\Cachable;
use Innoscripta\EloquentRepository\Repository\Contracts\Repository;

abstract class EloquentRepository implements Repository
{
    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var Builder
     */
    protected $entity;

    /**
     * EloquentRepository constructor.
     *
     * @param Cache $cache
     * @throws BindingResolutionException
     */
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
        $this->entity = $this->resolveEntity();
    }

    /**
     * Resolves entity.
     *
     * @return mixed
     * @throws BindingResolutionException
     */
    protected function resolveEntity()
    {
        return app()->make($this->entity());
    }

    /**
     * Defines entity.
     *
     * @return mixed
     */
    abstract protected function entity();

    /**
     * Creates model.
     *
     * @param array $properties
     *
     * @return Builder|Model
     */
    public function create(array $properties)
    {
        return $this->entity->create($properties);
    }

    /**
     * Returns all models.
     *
     * @return Builder[]|Collection
     * @throws BindingResolutionException
     */
    public function all()
    {
        return $this->get();
    }

    /**
     * Returns all models with selected columns.
     *
     * @param array $columns
     *
     * @return Builder[]|Collection
     * @throws BindingResolutionException
     */
    public function get(array $columns = ['*'])
    {
        if ($this instanceof Cachable) {
            return $this->cache->remember(
                $this->cacheKey().'.'.implode(',', $columns),
                $this->cacheTTL(),
                function () use ($columns) {
                    return $this->entity->get($columns);
                }
            );
        }

        return $this->entity->get($columns);
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
        return $this->entity->paginate($perPage);
    }

    /**
     * Finds models with "where" condition.
     *
     * @param string|array $column
     * @param mixed $value
     *
     * @return Builder[]|Collection
     */
    public function findWhere($column, $value = null)
    {
        if (is_array($column)) {
            return $this->entity->where($column)->get();
        }

        return $this->entity->where($column, $value)->get();
    }

    /**
     * Finds models with "whereIn" condition.
     *
     * @param string $column
     * @param array $values
     *
     * @return Builder[]|Collection
     */
    public function findWhereIn(string $column, array $values)
    {
        return $this->entity->whereIn($column, $values)->get();
    }

    /**
     * Finds first model with "where" condition.
     *
     * @param string|array $column
     * @param mixed $value
     *
     * @return Builder|Model|object|null
     */
    public function findWhereFirst($column, $value = null)
    {
        if (is_array($column)) {
            $model = $this->entity->where($column)->first();
        } else {
            $model = $this->entity->where($column, $value)->first();
        }

        if (! $model) {
            throw (new ModelNotFoundException)->setModel(
                get_class($this->entity->getModel())
            );
        }

        return $model;
    }

    /**
     * Finds first model with "whereIn" condition.
     *
     * @param string $column
     * @param array $values
     *
     * @return Builder|Model|object|null
     */
    public function findWhereInFirst(string $column, array $values)
    {
        $model = $this->entity->whereIn($column, $values)->first();

        if (! $model) {
            throw (new ModelNotFoundException)->setModel(
                get_class($this->entity->getModel())
            );
        }

        return $model;
    }

    /**
     * Finds a model with ID and updates it with given properties.
     *
     * @param int|string $modelId
     * @param array $properties
     *
     * @return Builder|Model
     * @throws BindingResolutionException
     */
    public function findAndUpdate($modelId, array $properties)
    {
        $model = $this->find($modelId);

        return $this->update($model, $properties);
    }

    /**
     * Finds a model with ID.
     *
     * @param int|string $modelId
     *
     * @return Builder|Builder[]|Collection|Model|null
     * @throws BindingResolutionException
     */
    public function find($modelId)
    {
        if ($this instanceof Cachable) {
            $model = $this->cache->remember(
                $this->cacheKey().'.'.$modelId,
                $this->cacheTTL(),
                function () use ($modelId) {
                    return $this->entity->find($modelId);
                }
            );
        } else {
            $model = $this->entity->find($modelId);
        }

        if (! $model) {
            throw (new ModelNotFoundException)->setModel(
                get_class($this->entity->getModel()),
                $modelId
            );
        }

        return $model;
    }

    /**
     * Updates a model given properties.
     *
     * @param Model $model
     * @param array $properties
     *
     * @return Builder|Model
     * @throws BindingResolutionException
     */
    public function update($model, array $properties)
    {
        if ($this instanceof Cachable) {
            $this->forgetCache($model);
        }

        $model->fill($properties)->save();

        return $model->refresh();
    }

    /**
     * Finds a model with ID and deletes it.
     *
     * @param int|string $modelId
     *
     * @return bool|mixed|null
     * @throws Exception
     */
    public function findAndDelete($modelId)
    {
        $model = $this->find($modelId);

        return $this->delete($model);
    }

    /**
     * Deletes a model.
     *
     * @param Model $model
     *
     * @return bool|mixed|null
     * @throws Exception
     */
    public function delete($model)
    {
        if ($this instanceof Cachable) {
            $this->forgetCache($model);
        }

        return $model->delete();
    }

    /**
     * Finds a soft deleted model with given ID and restores it.
     *
     * @param int|string $modelId
     *
     * @return bool|null
     */
    public function findAndRestore($modelId)
    {
        $model = $this->findFromTrashed($modelId);

        return $this->restore($model);
    }

    /**
     * Finds a soft deleted model with given ID.
     *
     * @param int|string $modelId
     *
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function findFromTrashed($modelId)
    {
        $model = $this->entity->onlyTrashed()->find($modelId);

        if (! $model) {
            throw (new ModelNotFoundException)->setModel(
                get_class($this->entity->getModel()),
                $modelId
            );
        }

        return $model;
    }

    /**
     * Restores soft deleted model.
     *
     * @param Model $model
     *
     * @return bool|null
     */
    public function restore($model)
    {
        return $model->restore();
    }

    /**
     * Sets listed criteria for entity.
     *
     * @param mixed ...$criteria
     *
     * @return self
     */
    public function withCriteria(...$criteria)
    {
        $criteria = Arr::flatten($criteria);

        foreach ($criteria as $criterion) {
            /* @var Criteria\Criteria $criterion */
            $this->entity = $criterion->apply($this->entity);
        }

        return $this;
    }

    /**
     * Defines cache time-to-live seconds.
     *
     * @return int
     */
    public function cacheTTL(): int
    {
        return 3600;
    }

    /**
     * Removes cache for model.
     *
     * @param Model $model
     * @throws BindingResolutionException
     */
    public function forgetCache($model): void
    {
        $this->cache->forget($this->cacheKey().'.*');
        $this->cache->forget($this->cacheKey().'.'.$model->id);
    }

    /**
     * Defines cache key.
     *
     * @return string
     * @throws BindingResolutionException
     */
    public function cacheKey(): string
    {
        return $this->resolveEntity()->getTable();
    }
}
