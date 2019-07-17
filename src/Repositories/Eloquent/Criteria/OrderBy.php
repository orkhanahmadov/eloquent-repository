<?php

namespace Innoscripta\EloquentRepositoryGenerator\Repositories\Eloquent\Criteria;

use Innoscripta\EloquentRepositoryGenerator\Repositories\Criteria\Criterion;

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
     * @param $entity
     *
     * @return mixed
     */
    public function apply($entity)
    {
        return $entity->orderBy($this->column, $this->sortBy);
    }
}
