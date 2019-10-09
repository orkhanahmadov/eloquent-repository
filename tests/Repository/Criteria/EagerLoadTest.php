<?php

namespace Orkhanahmadov\EloquentRepository\Tests\Repository\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Orkhanahmadov\EloquentRepository\EloquentRepository;
use Orkhanahmadov\EloquentRepository\Repository\Criteria\EagerLoad;
use Orkhanahmadov\EloquentRepository\Tests\fixtures\ModelOne;
use Orkhanahmadov\EloquentRepository\Tests\TestCase;

class EagerLoadTest extends TestCase
{
    public function testEagerLoadCriterion()
    {
        $repositoryClass = new \ReflectionClass(EloquentRepository::class);
        $modelInstanceProp = $repositoryClass->getProperty('modelInstance');
        $modelInstanceProp->setAccessible(true);
        $builderClass = new \ReflectionClass(Builder::class);
        $eagerLoadProp = $builderClass->getProperty('eagerLoad');
        $eagerLoadProp->setAccessible(true);

        $result = $this->repository
            ->entity(ModelOne::class)
            ->withCriteria(new EagerLoad('someRelationName'));

        $eagerLoadedRelations = $eagerLoadProp->getValue($modelInstanceProp->getValue($result));
        $this->assertCount(1, $eagerLoadedRelations);
        $this->assertTrue(isset($eagerLoadedRelations['someRelationName']));
    }
}
