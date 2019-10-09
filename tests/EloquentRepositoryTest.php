<?php

namespace Orkhanahmadov\EloquentRepository\Tests;

use Illuminate\Contracts\Container\BindingResolutionException;
use Orkhanahmadov\EloquentRepository\Tests\fixtures\ModelOneRepository;
use Orkhanahmadov\EloquentRepository\Tests\fixtures\ModelTwo;

class EloquentRepositoryTest extends TestCase
{
    public function testCacheKeyReturnsTableName()
    {
        $result = $this->repository->entity(ModelTwo::class)->cacheKey();

        $this->assertSame('model_two', $result);
    }

    public function testCacheTTL()
    {
        $result = $this->repository->cacheTTL(1800);

        $this->assertSame(1800, $result);
    }

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
