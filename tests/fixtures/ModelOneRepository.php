<?php

namespace Orkhanahmadov\EloquentRepository\Tests\fixtures;

use Orkhanahmadov\EloquentRepository\EloquentRepository;

class ModelOneRepository extends EloquentRepository
{
    protected $entity = ModelOne::class;
}
