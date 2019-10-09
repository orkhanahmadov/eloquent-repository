<?php

namespace Orkhanahmadov\EloquentRepository\Repository\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Orkhanahmadov\EloquentRepository\Repository\Contracts\Criterion;

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
     * @param Builder $model
     *
     * @return Builder
     */
    public function apply($model)
    {
        return $model->orderByDesc($this->column);
    }
}
