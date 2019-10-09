<?php

namespace Orkhanahmadov\EloquentRepository\Tests\Repository\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Orkhanahmadov\EloquentRepository\EloquentRepository;
use Orkhanahmadov\EloquentRepository\Repository\Eloquent\Criteria\EagerLoad;
use Orkhanahmadov\EloquentRepository\Tests\fixtures\ModelOne;
use Orkhanahmadov\EloquentRepository\Tests\TestCase;

class EagerLoadTest extends TestCase
{
    public function testEagerLoadCriterion()
    {
        $repository = new \ReflectionClass(EloquentRepository::class);
        $property = $repository->getProperty('modelInstance');
        $property->setAccessible(true);
        $builder = new \ReflectionClass(Builder::class);
        $eagerLoadProperty = $builder->getProperty('eagerLoad');
        $eagerLoadProperty->setAccessible(true);

        $result = $this->repository
            ->entity(ModelOne::class)
            ->withCriteria(new EagerLoad('modelTwo'));

        $eagerLoadedRelations = $eagerLoadProperty->getValue($property->getValue($result));
        $this->assertCount(1, $eagerLoadedRelations);
        $this->assertTrue(isset($eagerLoadedRelations['modelTwo']));
    }
}
