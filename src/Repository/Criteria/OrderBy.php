<?php

namespace Orkhanahmadov\EloquentRepository\Repository\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Orkhanahmadov\EloquentRepository\Repository\Contracts\Criterion;

class OrderBy implements Criterion
{
    /**
     * @var string
     */
    protected $column;
    /**
     * @var string
     */
    protected $sortBy;

    /**
     * OrderBy constructor.
     *
     * @param string $column
     * @param string $sortBy
     */
    public function __construct(string $column, string $sortBy)
    {
        $this->column = $column;
        $this->sortBy = $sortBy;
    }

    /**
     * @param Builder $model
     *
     * @return Builder
     */
    public function apply($model)
    {
        return $model->orderBy($this->column, $this->sortBy);
    }
}
