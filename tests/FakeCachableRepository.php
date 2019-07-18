<?php

namespace Innoscripta\EloquentRepository\Tests;

use Innoscripta\EloquentRepository\Repository\Contracts\Cachable;
use Innoscripta\EloquentRepository\Repository\EloquentRepository;

class FakeCachableRepository extends EloquentRepository implements Cachable
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
