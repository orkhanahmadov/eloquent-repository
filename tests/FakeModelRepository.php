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
     * @var string
     */
    protected $entity = Model::class;
}
