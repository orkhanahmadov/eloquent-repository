<?php

namespace Orkhanahmadov\EloquentRepository;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Orkhanahmadov\EloquentRepository\Repository\Concerns\CreatesEntity;
use Orkhanahmadov\EloquentRepository\Repository\Concerns\GetsEntity;
use Orkhanahmadov\EloquentRepository\Repository\Criteria;
use Illuminate\Contracts\Container\BindingResolutionException;
use Orkhanahmadov\EloquentRepository\Repository\Contracts\Cacheable;
use Orkhanahmadov\EloquentRepository\Repository\Contracts\Repository;

abstract class EloquentRepository implements Repository
{
    use CreatesEntity;
    use GetsEntity;

    /**
     * @var Application
     */
    private $application;
    /**
     * @var Cache
     */
    protected $cache;
    /**
     * @var int
     */
    protected $cacheTTL = 3600;
    /**
     * @var Builder
     */
    protected $entity;

    /**
     * EloquentRepository constructor.
     *
     * @param Application $application
     * @param Cache $cache
     * @throws BindingResolutionException
     */
    public function __construct(Application $application, Cache $cache)
    {
        $this->application = $application;
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
        return $this->application->make($this->entity());
    }

    /**
     * Defines entity.
     *
     * @return mixed
     */
    abstract protected function entity();

    /**
     * Finds a model with ID and deletes it.
     *
     * @param int|string $modelId
     *
     * @return bool|mixed|null
     * @throws \Exception
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
     * @throws \Exception
     */
    public function delete($model)
    {
        if ($this instanceof Cacheable) {
            $this->invalidateCache($model);
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
        if (! method_exists($this->entity, 'restore')) {
            throw new \BadMethodCallException('Model is not using "soft delete" feature.');
        }

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
        if (! method_exists($this->entity, 'restore')) {
            throw new \BadMethodCallException('Model is not using "soft delete" feature.');
        }

        return $model->restore();
    }

//    public function sync($model, string $relation, $data)
//    {
//        $model->{$relation}()->sync($data);
//
//        return $model;
//    }

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
     * Removes cache for model.
     *
     * @param Model $model
     * @throws BindingResolutionException
     */
    public function invalidateCache($model): void
    {
        $this->cache->forget(
            $this->cacheKey().'.*'
        );
        $this->cache->forget(
            $this->cacheKey().'.'.$model->id
        );
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

    /**
     * Get cache time-to-live value from property or method if available.
     *
     * @return int
     */
    private function cacheTTLValue(): int
    {
        if (method_exists($this, 'cacheTTL')) {
            return $this->cacheTTL();
        }

        return $this->cacheTTL;
    }
}
