<?php

namespace Innoscripta\EloquentRepositoryGenerator\Repositories\Eloquent\Criteria;

use Innoscripta\EloquentRepositoryGenerator\Repositories\Criteria\Criterion;

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
     * @param $entity
     *
     * @return mixed
     */
    public function apply($entity)
    {
        return $entity->orderByDesc($this->column);
    }
}
