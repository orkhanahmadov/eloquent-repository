<?php

namespace Innoscripta\EloquentRepository\Tests;

use Carbon\Carbon;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class RepositoryTest extends TestCase
{
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();

        DB::statement('CREATE TABLE models (id INT, name VARCHAR , deleted_at TIMESTAMP);');

        $this->repository = app()->make(FakeModelRepository::class);
    }

    public function testCreate()
    {
        $this->assertCount(0, Model::all());

        $result = $this->repository->create(['id' => 5, 'name' => 'model name']);

        $this->assertEquals(5, $result->id);
        $this->assertEquals('model name', $result->name);
        $this->assertCount(1, Model::all());
    }

    public function testAll()
    {
        Model::create(['id' => 5, 'name' => 'model1']);
        Model::create(['id' => 15, 'name' => 'model2']);

        $result = $this->repository->all();

        $this->assertCount(2, $result);
        $this->assertEquals([
            [
                'id' => 5,
                'name' => 'model1',
                'deleted_at' => null,
            ],
            [
                'id' => 15,
                'name' => 'model2',
                'deleted_at' => null,
            ],
        ], $result->toArray());
    }

    public function testGet()
    {
        Model::create(['id' => 5, 'name' => 'model1']);
        Model::create(['id' => 15, 'name' => 'model2']);

        $result = $this->repository->get(['id']);

        $this->assertCount(2, $result);
        $this->assertEquals([
            [
                'id' => 5,
            ],
            [
                'id' => 15,
            ],
        ], $result->toArray());
    }

    public function testGetCached()
    {
        Model::create(['id' => 5, 'name' => 'model1']);
        Model::create(['id' => 15, 'name' => 'model2']);
        $this->assertNull(Cache::get('models.*'));

        DB::enableQueryLog();
        $this->repository->get();
        $this->assertNotNull(Cache::get('models.*'));

        $this->repository->get();
        DB::disableQueryLog();
        $this->assertCount(1, DB::getQueryLog());
    }

    public function testPaginate()
    {
        $model1 = Model::create(['id' => 5, 'name' => 'model1']);
        Model::create(['id' => 15, 'name' => 'model2']);

        $result = $this->repository->paginate(1);

        $this->assertCount(1, $data = $result->toArray()['data']);
        $this->assertEquals($data[0]['id'], $model1->id);
    }

    public function testFind()
    {
        Model::create(['id' => 5, 'name' => 'model1']);
        Model::create(['id' => 15, 'name' => 'model2']);

        $result = $this->repository->find(15);

        $this->assertEquals(15, $result->id);
        $this->assertEquals('model2', $result->name);
    }

    public function testFindCached()
    {
        Model::create(['id' => 5, 'name' => 'model1']);
        Model::create(['id' => 15, 'name' => 'model2']);
        $this->assertNull(Cache::get('models.15'));

        DB::enableQueryLog();
        $this->repository->find(15);
        $this->assertNotNull(Cache::get('models.15'));

        $this->repository->find(15);
        DB::disableQueryLog();
        $this->assertCount(1, DB::getQueryLog());
    }

    public function testWhere()
    {
        Model::create(['id' => 5, 'name' => 'model name']);
        Model::create(['id' => 15, 'name' => 'model name']);
        Model::create(['id' => 25, 'name' => 'model3']);

        $result = $this->repository->findWhere('name', 'model name');
        $this->assertCount(2, $result);
        $this->assertEquals(5, $result[0]['id']);
        $this->assertEquals(15, $result[1]['id']);

        $result = $this->repository->findWhere([
            'id' => 15,
            'name' => 'model name',
        ]);
        $this->assertCount(1, $result);
        $this->assertEquals(15, $result[0]['id']);
    }

    public function testWhereIn()
    {
        Model::create(['id' => 5, 'name' => 'model1']);
        Model::create(['id' => 15, 'name' => 'model2']);
        Model::create(['id' => 25, 'name' => 'model3']);

        $result = $this->repository->findWhereIn('id', [5, 25]);
        $this->assertCount(2, $result);
        $this->assertEquals(5, $result[0]['id']);
        $this->assertEquals(25, $result[1]['id']);
    }

    public function testWhereFirst()
    {
        Model::create(['id' => 5, 'name' => 'model name']);
        Model::create(['id' => 15, 'name' => 'model name']);
        Model::create(['id' => 25, 'name' => 'model3']);

        $result = $this->repository->findWhereFirst('name', 'model name');
        $this->assertEquals(5, $result['id']);
    }

    public function testWhereInFirst()
    {
        Model::create(['id' => 5, 'name' => 'model1']);
        Model::create(['id' => 15, 'name' => 'model2']);
        Model::create(['id' => 25, 'name' => 'model3']);

        $result = $this->repository->findWhereInFirst('id', [15, 25]);
        $this->assertEquals(15, $result['id']);
    }

    public function testFindAndUpdate()
    {
        $model = Model::create(['id' => 5, 'name' => 'model name']);

        $result = $this->repository->findAndUpdate($model->id, [
            'name' => 'updated name',
        ]);
        $this->assertEquals('updated name', $result->name);
        $this->assertEquals('updated name', $model->refresh()->name);
    }

    public function testUpdate()
    {
        $model = Model::create(['id' => 5, 'name' => 'model name']);
        Cache::put('models.'.$model->id, $model, 100);

        $this->assertNotNull(Cache::get('models.'.$model->id));
        $result = $this->repository->update($model, [
            'name' => 'updated name',
        ]);
        $this->assertNull(Cache::get('models.'.$model->id));
        $this->assertEquals('updated name', $result->name);
        $this->assertEquals('updated name', $model->refresh()->name);
    }

    public function testFindAndDelete()
    {
        $model = Model::create(['id' => 5, 'name' => 'model name']);
        $this->assertNull($model->deleted_at);

        $result = $this->repository->findAndDelete($model->id);

        $this->assertNotNull($model->refresh()->deleted_at);
        $this->assertTrue($result);
    }

    public function testDelete()
    {
        $model = Model::create(['id' => 5, 'name' => 'model name']);
        Cache::put('models.'.$model->id, $model, 100);
        $this->assertNotNull(Cache::get('models.'.$model->id));
        $this->assertNull($model->deleted_at);

        $result = $this->repository->delete($model);

        $this->assertNull(Cache::get('models.'.$model->id));
        $this->assertNotNull($model->refresh()->deleted_at);
        $this->assertTrue($result);
    }

    public function testFindAndRestore()
    {
        $model = Model::create(['id' => 5, 'name' => 'model name', 'deleted_at' => Carbon::now()->subDay()]);
        $this->assertNotNull($model->deleted_at);

        $result = $this->repository->findAndRestore($model->id);

        $this->assertNull($model->refresh()->deleted_at);
        $this->assertTrue($result);
    }

    public function testFindFromTrashed()
    {
        $model = Model::create(['id' => 5, 'name' => 'model name', 'deleted_at' => Carbon::now()->subDay()]);

        $result = $this->repository->findFromTrashed($model->id);

        $this->assertEquals($model->id, $result->id);
    }

    public function testRestore()
    {
        $model = Model::create(['id' => 5, 'name' => 'model name', 'deleted_at' => Carbon::now()->subDay()]);
        $this->assertNotNull($model->deleted_at);

        $result = $this->repository->restore($model);

        $this->assertNull($model->refresh()->deleted_at);
        $this->assertTrue($result);
    }
}
