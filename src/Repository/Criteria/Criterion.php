<?php

namespace Orkhanahmadov\EloquentRepository\Repository\Criteria;

interface Criterion
{
    /**
     * @param $entity
     *
     * @return mixed
     */
    public function apply($entity);
}
