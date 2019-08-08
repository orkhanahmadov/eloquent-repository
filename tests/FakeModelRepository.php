<?php

namespace Orkhanahmadov\EloquentRepository\Tests;

use Orkhanahmadov\EloquentRepository\EloquentRepository;

class FakeModelRepository extends EloquentRepository
{
    /**
     * @var int
     */
    protected $cacheTTL = 500;

    /**
     * Defines entity.
     *
     * @return mixed
     */
    protected function entity()
    {
        return Model::class;
    }
}
