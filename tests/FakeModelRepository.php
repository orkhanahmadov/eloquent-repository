<?php

namespace Innoscripta\EloquentRepository\Tests;

use Innoscripta\EloquentRepository\Repository\EloquentRepository;

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
