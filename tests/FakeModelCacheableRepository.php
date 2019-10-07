<?php

namespace Orkhanahmadov\EloquentRepository\Tests;

use Orkhanahmadov\EloquentRepository\EloquentRepository;
use Orkhanahmadov\EloquentRepository\Repository\Contracts\Cacheable;

class FakeModelCacheableRepository extends EloquentRepository implements Cacheable
{
    /**
     * @var string
     */
    protected $entity = Model::class;

    public function cacheTTL(): int
    {
        return 1000;
    }
}
