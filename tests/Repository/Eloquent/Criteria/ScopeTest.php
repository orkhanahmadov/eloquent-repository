<?php

namespace Orkhanahmadov\EloquentRepository\Tests\Repository\Eloquent\Criteria;

use Illuminate\Support\Facades\DB;
use Orkhanahmadov\EloquentRepository\Tests\Model;
use Orkhanahmadov\EloquentRepository\Tests\TestCase;
use Orkhanahmadov\EloquentRepository\Tests\FakeModelRepository;
use Orkhanahmadov\EloquentRepository\Repository\Eloquent\Criteria\Scope;

class ScopeTest extends TestCase
{
    private $repository;

    public function testScope()
    {
        Model::create(['id' => 5, 'name' => 'model1']);
        Model::create(['id' => 15, 'name' => 'model2']);
        Model::create(['id' => 25, 'name' => 'model']);

        $result = $this->repository->withCriteria([
            new Scope('idGreaterThan10', 'idLessThan20'),
        ])->get();

        $this->assertCount(1, $result);
        $this->assertEquals(15, $result->first()->id);
    }

    protected function setUp(): void
    {
        parent::setUp();

        DB::statement('CREATE TABLE models (id INT, name VARCHAR, deleted_at TIMESTAMP);');

        $this->repository = app()->make(FakeModelRepository::class);
    }
}
