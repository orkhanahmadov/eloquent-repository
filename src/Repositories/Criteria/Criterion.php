<?php

namespace Innoscripta\EloquentRepositoryGenerator\Repositories\Criteria;

interface Criterion
{
    /**
     * @param $entity
     *
     * @return mixed
     */
    public function apply($entity);
}
