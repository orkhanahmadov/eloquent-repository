<?php

namespace Orkhanahmadov\EloquentRepository\Repository\Criteria;

interface Criteria
{
    /**
     * @param mixed ...$criteria
     *
     * @return self
     */
    public function withCriteria(...$criteria): self;
}
