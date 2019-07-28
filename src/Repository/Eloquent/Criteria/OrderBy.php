<?php

namespace Orkhanahmadov\EloquentRepository\Repository\Eloquent\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Orkhanahmadov\EloquentRepository\Repository\Criteria\Criterion;

class OrderBy implements Criterion
{
    /**
     * @var string
     */
    private $column;
    /**
     * @var string
     */
    private $sortBy;

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
     * @param Builder|mixed $entity
     *
     * @return Builder|mixed
     */
    public function apply($entity)
    {
        return $entity->orderBy($this->column, $this->sortBy);
    }
}
