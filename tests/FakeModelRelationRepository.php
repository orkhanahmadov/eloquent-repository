<?php

namespace Orkhanahmadov\EloquentRepository\Tests;

use Orkhanahmadov\EloquentRepository\EloquentRepository;

class FakeModelRelationRepository extends EloquentRepository
{
    /**
     * @var string
     */
    protected $entity = ModelRelation::class;
}
