<?php

namespace Orkhanahmadov\EloquentRepository\Repository\Contracts;

interface Criteria
{
    /**
     * @param mixed ...$criteria
     *
     * @return self
     */
    public function withCriteria(...$criteria): self;
}
