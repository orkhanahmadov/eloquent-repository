<?php

namespace Innoscripta\EloquentRepository\Tests;

use Innoscripta\EloquentRepository\EloquentRepository;

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
