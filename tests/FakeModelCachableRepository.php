<?php

namespace Orkhanahmadov\EloquentRepository\Tests;

use Orkhanahmadov\EloquentRepository\EloquentRepository;
use Orkhanahmadov\EloquentRepository\Repository\Contracts\Cachable;

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
