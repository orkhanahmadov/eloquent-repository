<?php

namespace Orkhanahmadov\EloquentRepository\Repository\Criteria;

interface Criterion
{
    /**
     * @param mixed $entity
     *
     * @return mixed
     */
    public function apply($entity);
}
