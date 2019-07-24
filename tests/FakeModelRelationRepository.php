<?php

namespace Orkhanahmadov\EloquentRepository\Tests;

use Orkhanahmadov\EloquentRepository\EloquentRepository;

class FakeModelRelationRepository extends EloquentRepository
{
    /**
     * Defines entity.
     *
     * @return mixed
     */
    protected function entity()
    {
        return ModelRelation::class;
    }
}
