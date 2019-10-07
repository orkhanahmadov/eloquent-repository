<?php

namespace Orkhanahmadov\EloquentRepository\Tests\Repository;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Orkhanahmadov\EloquentRepository\EloquentRepository;
use Orkhanahmadov\EloquentRepository\Tests\Model;
use Orkhanahmadov\EloquentRepository\Tests\TestCase;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Orkhanahmadov\EloquentRepository\Tests\FakeModelRepository;
use Orkhanahmadov\EloquentRepository\Tests\FakeModelRelationRepository;
use Orkhanahmadov\EloquentRepository\Tests\FakeModelCacheableRepository;

class EloquentRepositoryTest extends TestCase
{
    private $repository;
    private $modelRepository;
    private $cachedModelRepository;

    public function testCreate()
    {
        $this->assertCount(0, Model::all());

        $result = $this->repository->entity(Model::class)->create(['id' => 5, 'name' => 'model name']);

        $this->assertEquals(5, $result->id);
        $this->assertEquals('model name', $result->name);
        $this->assertCount(1, Model::all());
    }

    public function testAll()
    {
        Model::create(['id' => 5, 'name' => 'model1']);
        Model::create(['id' => 15, 'name' => 'model2']);

        $result = $this->modelRepository->all();

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

    public function testAllCached()
    {
        Model::create(['id' => 5, 'name' => 'model1']);
        Model::create(['id' => 15, 'name' => 'model2']);
        $this->assertNull(Cache::get('models.*'));

        DB::enableQueryLog();
        $this->cachedModelRepository->all();
        $this->assertNotNull(Cache::get('models.*'));

        $this->cachedModelRepository->all();
        DB::disableQueryLog();
        $this->assertCount(1, DB::getQueryLog());
    }

    public function testGet()
    {
        Model::create(['id' => 5, 'name' => 'model1']);
        Model::create(['id' => 15, 'name' => 'model2']);

        $result = $this->modelRepository->get(['id']);

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

    public function testPaginate()
    {
        $model1 = Model::create(['id' => 5, 'name' => 'model1']);
        Model::create(['id' => 15, 'name' => 'model2']);

        $result = $this->modelRepository->paginate(1);

        $this->assertCount(1, $data = $result->toArray()['data']);
        $this->assertEquals($data[0]['id'], $model1->id);
    }

    public function testFind()
    {
        Model::create(['id' => 5, 'name' => 'model1']);
        Model::create(['id' => 15, 'name' => 'model2']);

        $result = $this->modelRepository->find(15);

        $this->assertEquals(15, $result->id);
        $this->assertEquals('model2', $result->name);
    }

    public function testFindException()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage('No query results for model [Orkhanahmadov\EloquentRepository\Tests\Model] 15');
        Model::create(['id' => 5, 'name' => 'model1']);

        $this->modelRepository->find(15);
    }

    public function testFindCached()
    {
        Model::create(['id' => 5, 'name' => 'model1']);
        Model::create(['id' => 15, 'name' => 'model2']);
        $this->assertNull(Cache::get('models.15'));

        DB::enableQueryLog();
        $this->cachedModelRepository->find(15);
        $this->assertNotNull(Cache::get('models.15'));

        $this->cachedModelRepository->find(15);
        DB::disableQueryLog();
        $this->assertCount(1, DB::getQueryLog());
    }

    public function testGetWhere()
    {
        Model::create(['id' => 5, 'name' => 'model name']);
        Model::create(['id' => 15, 'name' => 'model name']);
        Model::create(['id' => 25, 'name' => 'model3']);

        $result = $this->modelRepository->getWhere('name', 'model name');
        $this->assertCount(2, $result);
        $this->assertEquals(5, $result[0]['id']);
        $this->assertEquals(15, $result[1]['id']);

        $result = $this->modelRepository->getWhere([
            'id' => 15,
            'name' => 'model name',
        ]);
        $this->assertCount(1, $result);
        $this->assertEquals(15, $result[0]['id']);
    }

    public function testGetWhereIn()
    {
        Model::create(['id' => 5, 'name' => 'model1']);
        Model::create(['id' => 15, 'name' => 'model2']);
        Model::create(['id' => 25, 'name' => 'model3']);

        $result = $this->modelRepository->getWhereIn('id', [5, 25]);
        $this->assertCount(2, $result);
        $this->assertEquals(5, $result[0]['id']);
        $this->assertEquals(25, $result[1]['id']);
    }

    public function testGetWhereFirst()
    {
        Model::create(['id' => 5, 'name' => 'model name']);
        Model::create(['id' => 15, 'name' => 'model name']);
        Model::create(['id' => 25, 'name' => 'model3']);

        $result1 = $this->modelRepository->getWhereFirst('name', 'model name');
        $result2 = $this->modelRepository->getWhereFirst(['name' => 'model3']);
        $this->assertEquals(5, $result1['id']);
        $this->assertEquals(25, $result2['id']);
    }

    public function testGetWhereFirstException()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage('No query results for model [Orkhanahmadov\EloquentRepository\Tests\Model].');
        Model::create(['id' => 5, 'name' => 'model name']);
        Model::create(['id' => 15, 'name' => 'model name']);

        $this->modelRepository->getWhereFirst('name', 'other model name');
    }

    public function testGetWhereInFirst()
    {
        Model::create(['id' => 5, 'name' => 'model1']);
        Model::create(['id' => 15, 'name' => 'model2']);
        Model::create(['id' => 25, 'name' => 'model3']);

        $result = $this->modelRepository->getWhereInFirst('id', [15, 25]);
        $this->assertEquals(15, $result['id']);
    }

    public function testGetWhereInFirstException()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage('No query results for model [Orkhanahmadov\EloquentRepository\Tests\Model].');
        Model::create(['id' => 5, 'name' => 'model name']);
        Model::create(['id' => 15, 'name' => 'model name']);

        $this->modelRepository->getWhereInFirst('id', [10, 25]);
    }

    public function testFindAndUpdate()
    {
        $model = Model::create(['id' => 5, 'name' => 'model name']);

        $result = $this->modelRepository->findAndUpdate($model->id, [
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
        $result = $this->cachedModelRepository->update($model, [
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

        $result = $this->modelRepository->findAndDelete($model->id);

        $this->assertNotNull($model->refresh()->deleted_at);
        $this->assertTrue($result);
    }

    public function testDelete()
    {
        $model = Model::create(['id' => 5, 'name' => 'model name']);
        Cache::put('models.'.$model->id, $model, 100);
        $this->assertNotNull(Cache::get('models.'.$model->id));
        $this->assertNull($model->deleted_at);

        $result = $this->cachedModelRepository->delete($model);

        $this->assertNull(Cache::get('models.'.$model->id));
        $this->assertNotNull($model->refresh()->deleted_at);
        $this->assertTrue($result);
    }

    public function testFindAndRestore()
    {
        $model = Model::create(['id' => 5, 'name' => 'model name', 'deleted_at' => Carbon::now()->subDay()]);
        $this->assertNotNull($model->deleted_at);

        $result = $this->modelRepository->findAndRestore($model->id);

        $this->assertNull($model->refresh()->deleted_at);
        $this->assertTrue($result);
    }

    public function testFindFromTrashed()
    {
        $model = Model::create(['id' => 5, 'name' => 'model name', 'deleted_at' => Carbon::now()->subDay()]);

        $result = $this->modelRepository->findFromTrashed($model->id);

        $this->assertEquals($model->id, $result->id);
    }

    public function testFindFromTrashedNotFoundException()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage('No query results for model [Orkhanahmadov\EloquentRepository\Tests\Model] 10');
        Model::create(['id' => 5, 'name' => 'model name', 'deleted_at' => now()->subDay()]);

        $this->modelRepository->findFromTrashed(10);
    }

    public function testFindFromTrashedBadMethodCallException()
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Model is not using "soft delete" feature.');

        DB::statement('CREATE TABLE model_relations (id INT, name VARCHAR);');
        $model = Model::create(['id' => 5, 'name' => 'model name']);
        $repository = app()->make(FakeModelRelationRepository::class);

        $repository->delete($model);
        $repository->findFromTrashed($model->id);
    }

    public function testRestore()
    {
        $model = Model::create(['id' => 5, 'name' => 'model name', 'deleted_at' => Carbon::now()->subDay()]);
        $this->assertNotNull($model->deleted_at);

        $result = $this->modelRepository->restore($model);

        $this->assertNull($model->refresh()->deleted_at);
        $this->assertTrue($result);
    }

    public function testRestoreBadMethodCallException()
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Model is not using "soft delete" feature.');

        DB::statement('CREATE TABLE model_relations (id INT, name VARCHAR);');
        $model = Model::create(['id' => 5, 'name' => 'model name']);
        $repository = app()->make(FakeModelRelationRepository::class);

        $repository->delete($model);
        $repository->restore($model);
    }

    public function testCacheKey()
    {
        $this->assertEquals((new Model())->getTable(), $this->cachedModelRepository->cacheKey());
    }

//    todo
//    public function testSyncWith()
//    {
//        $model = Model::create(['id' => 5, 'name' => 'model1']);
//
//        $result = $this->repository->sync($model, 'something', [1,2]);
//        $result = $this->repository->entity($model)->relation('something')->sync([1,2]);
//
//        $this->assertEquals(15, $result->id);
//        $this->assertEquals('model2', $result->name);
//    }

    public function testCacheTTLValueWithProperty()
    {
        $method = new \ReflectionMethod(FakeModelRepository::class, 'cacheTTLValue');
        $method->setAccessible(true);

        $this->assertEquals(500, $method->invoke(app()->make(FakeModelRepository::class)));
    }

    public function testCacheTTLValueWithMethod()
    {
        $method = new \ReflectionMethod(FakeModelCacheableRepository::class, 'cacheTTLValue');
        $method->setAccessible(true);

        $this->assertEquals(1000, $method->invoke(app()->make(FakeModelCacheableRepository::class)));
    }

    public function testInvalidateCache()
    {
        $model = Model::create(['id' => 5, 'name' => 'model name']);
        Cache::put('models.'.$model->id, $model, 100);

        $this->cachedModelRepository->invalidateCache($model);

        $this->assertNull(Cache::get('models.'.$model->id));
    }

    protected function setUp(): void
    {
        parent::setUp();

        DB::statement('CREATE TABLE models (id INT, name VARCHAR, deleted_at TIMESTAMP);');

        $this->repository = app()->make(EloquentRepository::class);
        $this->modelRepository = app()->make(FakeModelRepository::class);
        $this->cachedModelRepository = app()->make(FakeModelCacheableRepository::class);
    }
}
