<?php

namespace Innoscripta\EloquentRepositoryGenerator\Repositories\Criteria;

interface Criteria
{
    /**
     * @param mixed ...$criteria
     *
     * @return $this
     */
    public function withCriteria(...$criteria);
}
