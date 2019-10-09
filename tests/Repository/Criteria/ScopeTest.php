<?php

namespace Orkhanahmadov\EloquentRepository\Tests\Repository\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Orkhanahmadov\EloquentRepository\EloquentRepository;
use Orkhanahmadov\EloquentRepository\Repository\Criteria\Scope;
use Orkhanahmadov\EloquentRepository\Tests\fixtures\ModelOne;
use Orkhanahmadov\EloquentRepository\Tests\TestCase;

class ScopeTest extends TestCase
{
    public function testScopeCriterion()
    {
        $repositoryClass = new \ReflectionClass(EloquentRepository::class);
        $modelInstanceProp = $repositoryClass->getProperty('modelInstance');
        $modelInstanceProp->setAccessible(true);
        $builderClass = new \ReflectionClass(Builder::class);
        $queryProp = $builderClass->getProperty('query');
        $queryProp->setAccessible(true);

        $result = $this->repository
            ->entity(ModelOne::class)
            ->withCriteria(new Scope('idGreaterThan10', 'idLessThanOrEqual20'));


        $wheres = $queryProp->getValue($modelInstanceProp->getValue($result))->wheres;
        $this->assertCount(2, $wheres);
        $this->assertSame('id', $wheres[0]['column']);
        $this->assertSame('>', $wheres[0]['operator']);
        $this->assertSame(10, $wheres[0]['value']);
        $this->assertSame('id', $wheres[1]['column']);
        $this->assertSame('<=', $wheres[1]['operator']);
        $this->assertSame(20, $wheres[1]['value']);
    }
}
