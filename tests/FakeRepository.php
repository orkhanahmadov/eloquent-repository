<?php

namespace Innoscripta\EloquentRepository\Tests;

use Innoscripta\EloquentRepository\Repository\EloquentRepository;

class FakeRepository extends EloquentRepository
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
}
