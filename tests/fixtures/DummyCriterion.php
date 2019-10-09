<?php

namespace Orkhanahmadov\EloquentRepository\Tests\fixtures;

use Illuminate\Database\Eloquent\Model;
use Orkhanahmadov\EloquentRepository\Repository\Contracts\Criterion;

class DummyCriterion implements Criterion
{
    private $incrementing;

    public function __construct(bool $incrementing)
    {
        $this->incrementing = $incrementing;
    }

    /**
     * @param Model $model
     *
     * @return mixed
     */
    public function apply($model)
    {
        $model->incrementing = $this->incrementing;

        return $model;
    }
}
