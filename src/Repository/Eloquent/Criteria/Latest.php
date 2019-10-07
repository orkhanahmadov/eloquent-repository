<?php

namespace Orkhanahmadov\EloquentRepository\Repository\Eloquent\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Orkhanahmadov\EloquentRepository\Repository\Criteria\Criterion;

class Latest implements Criterion
{
    /**
     * @var string
     */
    protected $column;

    /**
     * Latest constructor.
     *
     * @param string $column
     */
    public function __construct(string $column = 'id')
    {
        $this->column = $column;
    }

    /**
     * @param Builder|mixed $model
     *
     * @return Builder|mixed
     */
    public function apply($model)
    {
        return $model->orderByDesc($this->column);
    }
}
