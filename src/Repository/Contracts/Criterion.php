<?php

namespace Orkhanahmadov\EloquentRepository\Repository\Contracts;

interface Criterion
{
    /**
     * @param mixed $model
     *
     * @return mixed
     */
    public function apply($model);
}
