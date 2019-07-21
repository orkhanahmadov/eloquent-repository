<?php

namespace Innoscripta\EloquentRepository\Tests\Repository\Eloquent\Criteria;

use Illuminate\Support\Facades\DB;
use Innoscripta\EloquentRepository\Repository\Eloquent\Criteria\EagerLoad;
use Innoscripta\EloquentRepository\Tests\FakeModelRepository;
use Innoscripta\EloquentRepository\Tests\Model;
use Innoscripta\EloquentRepository\Tests\ModelRelation;
use Innoscripta\EloquentRepository\Tests\TestCase;

class EagerLoadTest extends TestCase
{
    private $repository;

    public function testEagerLoad()
    {
        $model = Model::create(['id' => 5, 'name' => 'model1']);
        ModelRelation::create(['id' => 12, 'model_id' => $model->id]);

        $result = $this->repository->withCriteria(new EagerLoad('relations'))->find($model->id);

        $this->assertTrue($result->relationLoaded('relations'));
    }

    protected function setUp(): void
    {
        parent::setUp();

        DB::statement('CREATE TABLE models (id INT, name VARCHAR, deleted_at TIMESTAMP);');
        DB::statement('CREATE TABLE model_relations (id INT, model_id INT);');

        $this->repository = app()->make(FakeModelRepository::class);
    }
}
