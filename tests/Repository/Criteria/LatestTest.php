<?php

namespace Orkhanahmadov\EloquentRepository\Tests\Repository\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Orkhanahmadov\EloquentRepository\EloquentRepository;
use Orkhanahmadov\EloquentRepository\Repository\Criteria\Latest;
use Orkhanahmadov\EloquentRepository\Tests\fixtures\ModelOne;
use Orkhanahmadov\EloquentRepository\Tests\TestCase;

class LatestTest extends TestCase
{
    public function testLatestCriterion()
    {
        $repositoryClass = new \ReflectionClass(EloquentRepository::class);
        $modelInstanceProp = $repositoryClass->getProperty('modelInstance');
        $modelInstanceProp->setAccessible(true);
        $builderClass = new \ReflectionClass(Builder::class);
        $queryProp = $builderClass->getProperty('query');
        $queryProp->setAccessible(true);

        $result = $this->repository
            ->entity(ModelOne::class)
            ->withCriteria(new Latest('someColumnName'));

        $ordering = $queryProp->getValue($modelInstanceProp->getValue($result))->orders[0];
        $this->assertSame('someColumnName', $ordering['column']);
        $this->assertSame('desc', $ordering['direction']);
    }
}
