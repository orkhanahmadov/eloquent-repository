<?php

namespace Orkhanahmadov\EloquentRepository;

use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\{Builder, Model, ModelNotFoundException};
use Illuminate\Support\Arr;
use Orkhanahmadov\EloquentRepository\Repository\{Concerns\CreatesEntity,
    Concerns\DeletesEntity,
    Concerns\GetsEntity,
    Concerns\UpdatesEntity,
    Contracts\Repository,
    Criteria};

class EloquentRepository implements Repository
{
    use GetsEntity;
    use CreatesEntity;
    use UpdatesEntity;
    use DeletesEntity;

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
     * @var string|null
     */
    protected $entity = null;
    /**
     * @var Builder|Model
     */
    protected $model;

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

        if ($this->entity) {
            $this->resolveEntity();
        }
    }

    /**
     * @param Builder|Model $entity
     *
     * @return self
     *
     * @throws BindingResolutionException
     */
    public function entity($entity): self
    {
        $this->entity = $entity;
        $this->resolveEntity();

        return $this;
    }

    /**
     * Sets listed criteria for entity.
     *
     * @param mixed ...$criteria
     *
     * @return self
     */
    public function withCriteria(...$criteria): self
    {
        $criteria = Arr::flatten($criteria);

        foreach ($criteria as $criterion) {
            /* @var Criteria\Criteria $criterion */
            $this->model = $criterion->apply($this->model);
        }

        return $this;
    }

    /**
     * Defines cache key.
     *
     * @return string
     */
    public function cacheKey(): string
    {
        return $this->model->getTable();
    }

    /**
     * Removes cache for model.
     *
     * @param Model $model
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
     * Resolves entity.
     *
     * @throws BindingResolutionException
     */
    private function resolveEntity(): void
    {
        $this->model = $this->application->make($this->entity);
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

    /**
     * Throws ModelNotFoundException exception
     *
     * @param array|int $ids
     */
    private function throwModelNotFoundException($ids = [])
    {
        throw (new ModelNotFoundException)->setModel(
            get_class($this->model->getModel()),
            $ids
        );
    }
}
