<?php

namespace Orkhanahmadov\EloquentRepository\Tests;

use Illuminate\Contracts\Container\BindingResolutionException;
use Orkhanahmadov\EloquentRepository\Tests\fixtures\ModelOneRepository;

class EloquentRepositoryTest extends TestCase
{
    public function testThrowsBindingResolutionExceptionIfModelIsInvalid()
    {
        $this->expectException(BindingResolutionException::class);
        $this->expectExceptionMessage('Target class [NonExistingModel] does not exist.');

        $this->repository->entity('NonExistingModel');
    }

    public function testThrowsInvalidArgumentExceptionIfNotInstanceOfModel()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Orkhanahmadov\EloquentRepository\Tests\fixtures\ModelOneRepository ' .
            'is not instance of "Illuminate\Database\Eloquent\Model"'
        );

        $this->repository->entity(ModelOneRepository::class);
    }
}
