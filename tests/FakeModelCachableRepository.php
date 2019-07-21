<?php

namespace Innoscripta\EloquentRepository\Tests;

use Innoscripta\EloquentRepository\EloquentRepository;
use Innoscripta\EloquentRepository\Repository\Contracts\Cachable;

class FakeModelCachableRepository extends EloquentRepository implements Cachable
{
    /**
     * Defines entity.
     *
     * @return string
     */
    protected function entity(): string
    {
        return Model::class;
    }
}
