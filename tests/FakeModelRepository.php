<?php

namespace Orkhanahmadov\EloquentRepository\Tests;

use Orkhanahmadov\EloquentRepository\EloquentRepository;

class FakeModelRepository extends EloquentRepository
{
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
