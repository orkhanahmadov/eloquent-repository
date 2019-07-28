<?php

namespace Orkhanahmadov\EloquentRepository\Repository\Eloquent\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Orkhanahmadov\EloquentRepository\Repository\Criteria\Criterion;

class Latest implements Criterion
{
    /**
     * @var string
     */
    private $column;

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
     * @param Builder|mixed $entity
     *
     * @return Builder|mixed
     */
    public function apply($entity)
    {
        return $entity->orderByDesc($this->column);
    }
}
