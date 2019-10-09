<?php

namespace Orkhanahmadov\EloquentRepository;

use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Orkhanahmadov\EloquentRepository\Repository\Concerns\CreatesEntity;
use Orkhanahmadov\EloquentRepository\Repository\Concerns\DeletesEntity;
use Orkhanahmadov\EloquentRepository\Repository\Concerns\SelectsEntity;
use Orkhanahmadov\EloquentRepository\Repository\Concerns\UpdatesEntity;
use Orkhanahmadov\EloquentRepository\Repository\Contracts\Criteria;
use Orkhanahmadov\EloquentRepository\Repository\Contracts\Criterion;
use Orkhanahmadov\EloquentRepository\Repository\Contracts\Repository;

class EloquentRepository implements Repository, Criteria
{
    use SelectsEntity;
    use CreatesEntity;
    use UpdatesEntity;
    use DeletesEntity;

    /**
     * @var Application
     */
    private $application;
    /**
     * @var Config
     */
    protected $config;
    /**
     * @var Cache
     */
    protected $cache;
    /**
     * @var string|null
     */
    protected $entity = null;
    /**
     * @var Builder|Model
     */
    protected $resolvedEntity;
    /**
     * @var Model|null
     */
    protected $model = null;
    /**
     * @var string|null
     */
    protected $relation = null;

    /**
     * EloquentRepository constructor.
     *
     * @param Application $application
     * @param Config $config
     * @param Cache $cache
     *
     * @throws BindingResolutionException
     */
    public function __construct(Application $application, Config $config, Cache $cache)
    {
        $this->application = $application;
        $this->config = $config;
        $this->cache = $cache;

        if ($this->entity) {
            $this->resolveEntity();
        }
    }

    /**
     * @param string $entity
     *
     * @return self
     * @throws BindingResolutionException
     */
    public function entity(string $entity): self
    {
        $this->entity = $entity;
        $this->resolveEntity();

        return $this;
    }

    /**
     * @param string $relation
     *
     * @return self
     */
    public function relation(string $relation): self
    {
        $this->relation = $relation;

        return $this;
    }

    /**
     * @param Model $model
     *
     * @return self
     */
    public function model(Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Sets listed criteria for entity.
     *
     * @param mixed ...$criteria
     *
     * @return Criteria
     */
    public function withCriteria(...$criteria): Criteria
    {
        $criteria = Arr::flatten($criteria);

        foreach ($criteria as $criterion) {
            if (! $criterion instanceof Criterion) {
                throw new \InvalidArgumentException(
                    get_class($criterion) .
                    ' is not an instance of Orkhanahmadov\EloquentRepository\Repository\Criteria\Criterion'
                );
            }

            /* @var Criterion $criterion */
            $this->resolvedEntity = $criterion->apply($this->resolvedEntity);
        }

        return $this;
    }

    /**
     * Resolves entity.
     *
     * @throws BindingResolutionException
     */
    private function resolveEntity(): void
    {
        $this->resolvedEntity = $this->application->make($this->entity);

        if (! $this->resolvedEntity instanceof Model) {
            throw new \InvalidArgumentException(
                $this->entity . ' is not instance of "Illuminate\Database\Eloquent\Model"'
            );
        }
    }
}
