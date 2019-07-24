<?php

namespace Orkhanahmadov\EloquentRepository\Repository\Eloquent\Criteria;

use Illuminate\Support\Arr;
use Orkhanahmadov\EloquentRepository\Repository\Criteria\Criterion;

class EagerLoad implements Criterion
{
    /**
     * @var array
     */
    protected $relations;

    /**
     * EagerLoad constructor.
     *
     * @param mixed ...$relations
     */
    public function __construct(...$relations)
    {
        $this->relations = Arr::flatten($relations);
    }

    /**
     * @param $entity
     *
     * @return mixed
     */
    public function apply($entity)
    {
        return $entity->with($this->relations);
    }
}
