<?php

namespace Innoscripta\EloquentRepository\Tests\Repository\Eloquent\Criteria;

use Illuminate\Support\Facades\DB;
use Innoscripta\EloquentRepository\Repository\Eloquent\Criteria\Latest;
use Innoscripta\EloquentRepository\Tests\FakeRepository;
use Innoscripta\EloquentRepository\Tests\Model;
use Innoscripta\EloquentRepository\Tests\TestCase;

class LatestTest extends TestCase
{
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();

        DB::statement('CREATE TABLE models (id INT, name VARCHAR, deleted_at TIMESTAMP);');

        $this->repository = app()->make(FakeRepository::class);
    }

    public function testLatest()
    {
        Model::create(['id' => 5, 'name' => 'model1']);
        Model::create(['id' => 15, 'name' => 'model1']);

        $result = $this->repository->withCriteria(new Latest())->get();

        $this->assertEquals(15, $result->first()->id);
    }
}
