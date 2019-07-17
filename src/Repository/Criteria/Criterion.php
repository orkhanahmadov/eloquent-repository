<?php

namespace Innoscripta\EloquentRepository\Repository\Criteria;

interface Criterion
{
    /**
     * @param $entity
     *
     * @return mixed
     */
    public function apply($entity);
}
