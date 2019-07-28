<?php

namespace Orkhanahmadov\EloquentRepository\Repository\Criteria;

use Illuminate\Database\Eloquent\Builder;

interface Criterion
{
    /**
     * @param Builder|mixed $entity
     *
     * @return Builder|mixed
     */
    public function apply($entity);
}
