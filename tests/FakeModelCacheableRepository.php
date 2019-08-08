<?php

namespace Orkhanahmadov\EloquentRepository\Tests;

use Orkhanahmadov\EloquentRepository\EloquentRepository;
use Orkhanahmadov\EloquentRepository\Repository\Contracts\Cacheable;

class FakeModelCacheableRepository extends EloquentRepository implements Cacheable
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

    public function cacheTTL(): int
    {
        return 1000;
    }
}
