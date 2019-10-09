<?php

namespace Orkhanahmadov\EloquentRepository\Tests;

use Illuminate\Contracts\Container\BindingResolutionException;
use Orkhanahmadov\EloquentRepository\EloquentRepository;
use Orkhanahmadov\EloquentRepository\Tests\fixtures\DummyCriterion;
use Orkhanahmadov\EloquentRepository\Tests\fixtures\ModelOne;
use Orkhanahmadov\EloquentRepository\Tests\fixtures\ModelOneRepository;

class EloquentRepositoryTest extends TestCase
{
    public function testWithCriteriaSetsPropertyToModelFromCriterion()
    {
        $class = new \ReflectionClass(EloquentRepository::class);
        $property = $class->getProperty('resolvedEntity');
        $property->setAccessible(true);

        $result = $this->repository
            ->entity(ModelOne::class)
            ->withCriteria(new DummyCriterion(true));

        $this->assertTrue($property->getValue($result)->incrementing);
    }

    public function testThrowsInvalidArgumentExceptionIfCriterionIsNotInstanceOfCriterion()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Orkhanahmadov\EloquentRepository\Tests\fixtures\ModelOne ' .
            'is not an instance of Orkhanahmadov\EloquentRepository\Repository\Criteria\Criterion'
        );

        $this->repository->withCriteria(new ModelOne());
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
