<?php

namespace Orkhanahmadov\EloquentRepository\Repository\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Orkhanahmadov\EloquentRepository\Repository\Contracts\Criterion;

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
     * @param Builder $model
     *
     * @return Builder
     */
    public function apply($model)
    {
        return $model->with($this->relations);
    }
}
