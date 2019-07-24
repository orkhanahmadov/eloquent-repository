<?php

namespace Orkhanahmadov\EloquentRepository\Tests\Repository\Eloquent\Criteria;

use Illuminate\Support\Facades\DB;
use Orkhanahmadov\EloquentRepository\Repository\Eloquent\Criteria\Latest;
use Orkhanahmadov\EloquentRepository\Tests\FakeModelRepository;
use Orkhanahmadov\EloquentRepository\Tests\Model;
use Orkhanahmadov\EloquentRepository\Tests\TestCase;

class LatestTest extends TestCase
{
    private $repository;

    public function testLatest()
    {
        Model::create(['id' => 5, 'name' => 'model1']);
        Model::create(['id' => 15, 'name' => 'model1']);

        $result = $this->repository->withCriteria(new Latest())->get();

        $this->assertEquals(15, $result->first()->id);
    }

    protected function setUp(): void
    {
        parent::setUp();

        DB::statement('CREATE TABLE models (id INT, name VARCHAR, deleted_at TIMESTAMP);');

        $this->repository = app()->make(FakeModelRepository::class);
    }
}
