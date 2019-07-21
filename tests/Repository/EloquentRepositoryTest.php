<?php

namespace Innoscripta\EloquentRepository\Tests\Repository;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Innoscripta\EloquentRepository\Tests\Model;
use Innoscripta\EloquentRepository\Tests\TestCase;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Innoscripta\EloquentRepository\Tests\FakeRepository;
use Innoscripta\EloquentRepository\Tests\FakeCachableRepository;

class EloquentRepositoryTest extends TestCase
{
    private $repository;
    private $cachedRepository;

    protected function setUp(): void
    {
        parent::setUp();

        DB::statement('CREATE TABLE models (id INT, name VARCHAR, deleted_at TIMESTAMP);');

        $this->repository = app()->make(FakeRepository::class);
        $this->cachedRepository = app()->make(FakeCachableRepository::class);
    }

    public function testCreate()
    {
        $this->assertCount(0, Model::all());

        $result = $this->cachedRepository->create(['id' => 5, 'name' => 'model name']);

        $this->assertEquals(5, $result->id);
        $this->assertEquals('model name', $result->name);
        $this->assertCount(1, Model::all());
    }

    public function testAll()
    {
        Model::create(['id' => 5, 'name' => 'model1']);
        Model::create(['id' => 15, 'name' => 'model2']);

        $result = $this->cachedRepository->all();

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
        $this->cachedRepository->get();
        $this->assertNotNull(Cache::get('models.*'));

        $this->cachedRepository->get();
        DB::disableQueryLog();
        $this->assertCount(1, DB::getQueryLog());
    }

    public function testPaginate()
    {
        $model1 = Model::create(['id' => 5, 'name' => 'model1']);
        Model::create(['id' => 15, 'name' => 'model2']);

        $result = $this->cachedRepository->paginate(1);

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

    public function testFindException()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage('No query results for model [Innoscripta\EloquentRepository\Tests\Model] 15');
        Model::create(['id' => 5, 'name' => 'model1']);

        $this->repository->find(15);
    }

    public function testFindCached()
    {
        Model::create(['id' => 5, 'name' => 'model1']);
        Model::create(['id' => 15, 'name' => 'model2']);
        $this->assertNull(Cache::get('models.15'));

        DB::enableQueryLog();
        $this->cachedRepository->find(15);
        $this->assertNotNull(Cache::get('models.15'));

        $this->cachedRepository->find(15);
        DB::disableQueryLog();
        $this->assertCount(1, DB::getQueryLog());
    }

    public function testFindWhere()
    {
        Model::create(['id' => 5, 'name' => 'model name']);
        Model::create(['id' => 15, 'name' => 'model name']);
        Model::create(['id' => 25, 'name' => 'model3']);

        $result = $this->cachedRepository->findWhere('name', 'model name');
        $this->assertCount(2, $result);
        $this->assertEquals(5, $result[0]['id']);
        $this->assertEquals(15, $result[1]['id']);

        $result = $this->cachedRepository->findWhere([
            'id' => 15,
            'name' => 'model name',
        ]);
        $this->assertCount(1, $result);
        $this->assertEquals(15, $result[0]['id']);
    }

    public function testFindWhereIn()
    {
        Model::create(['id' => 5, 'name' => 'model1']);
        Model::create(['id' => 15, 'name' => 'model2']);
        Model::create(['id' => 25, 'name' => 'model3']);

        $result = $this->cachedRepository->findWhereIn('id', [5, 25]);
        $this->assertCount(2, $result);
        $this->assertEquals(5, $result[0]['id']);
        $this->assertEquals(25, $result[1]['id']);
    }

    public function testFindWhereFirst()
    {
        Model::create(['id' => 5, 'name' => 'model name']);
        Model::create(['id' => 15, 'name' => 'model name']);
        Model::create(['id' => 25, 'name' => 'model3']);

        $result1 = $this->repository->findWhereFirst('name', 'model name');
        $result2 = $this->repository->findWhereFirst(['name' => 'model3']);
        $this->assertEquals(5, $result1['id']);
        $this->assertEquals(25, $result2['id']);
    }

    public function testFindWhereFirstException()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage('No query results for model [Innoscripta\EloquentRepository\Tests\Model].');
        Model::create(['id' => 5, 'name' => 'model name']);
        Model::create(['id' => 15, 'name' => 'model name']);

        $this->repository->findWhereFirst('name', 'other model name');
    }

    public function testFindWhereInFirst()
    {
        Model::create(['id' => 5, 'name' => 'model1']);
        Model::create(['id' => 15, 'name' => 'model2']);
        Model::create(['id' => 25, 'name' => 'model3']);

        $result = $this->cachedRepository->findWhereInFirst('id', [15, 25]);
        $this->assertEquals(15, $result['id']);
    }

    public function testFindWhereInFirstException()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage('No query results for model [Innoscripta\EloquentRepository\Tests\Model].');
        Model::create(['id' => 5, 'name' => 'model name']);
        Model::create(['id' => 15, 'name' => 'model name']);

        $this->repository->findWhereInFirst('id', [10, 25]);
    }

    public function testFindAndUpdate()
    {
        $model = Model::create(['id' => 5, 'name' => 'model name']);

        $result = $this->cachedRepository->findAndUpdate($model->id, [
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
        $result = $this->cachedRepository->update($model, [
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

        $result = $this->cachedRepository->findAndDelete($model->id);

        $this->assertNotNull($model->refresh()->deleted_at);
        $this->assertTrue($result);
    }

    public function testDelete()
    {
        $model = Model::create(['id' => 5, 'name' => 'model name']);
        Cache::put('models.'.$model->id, $model, 100);
        $this->assertNotNull(Cache::get('models.'.$model->id));
        $this->assertNull($model->deleted_at);

        $result = $this->cachedRepository->delete($model);

        $this->assertNull(Cache::get('models.'.$model->id));
        $this->assertNotNull($model->refresh()->deleted_at);
        $this->assertTrue($result);
    }

    public function testFindAndRestore()
    {
        $model = Model::create(['id' => 5, 'name' => 'model name', 'deleted_at' => Carbon::now()->subDay()]);
        $this->assertNotNull($model->deleted_at);

        $result = $this->cachedRepository->findAndRestore($model->id);

        $this->assertNull($model->refresh()->deleted_at);
        $this->assertTrue($result);
    }

    public function testFindFromTrashed()
    {
        $model = Model::create(['id' => 5, 'name' => 'model name', 'deleted_at' => Carbon::now()->subDay()]);

        $result = $this->cachedRepository->findFromTrashed($model->id);

        $this->assertEquals($model->id, $result->id);
    }

    public function testFindFromTrashedException()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage('No query results for model [Innoscripta\EloquentRepository\Tests\Model] 10');
        Model::create(['id' => 5, 'name' => 'model name', 'deleted_at' => now()->subDay()]);

        $this->repository->findFromTrashed(10);
    }

    public function testRestore()
    {
        $model = Model::create(['id' => 5, 'name' => 'model name', 'deleted_at' => Carbon::now()->subDay()]);
        $this->assertNotNull($model->deleted_at);

        $result = $this->cachedRepository->restore($model);

        $this->assertNull($model->refresh()->deleted_at);
        $this->assertTrue($result);
    }

    public function testCacheKey()
    {
        $this->assertEquals('models', $this->cachedRepository->cacheKey());
    }

    public function testCacheTTL()
    {
        $this->assertEquals(3600, $this->cachedRepository->cacheTTL());
    }

    public function testForgetCache()
    {
        $model = Model::create(['id' => 5, 'name' => 'model name']);
        Cache::put('models.*', Model::all(), 100);
        Cache::put('models.'.$model->id, $model, 100);

        $this->cachedRepository->forgetCache($model);

        $this->assertNull(Cache::get('models.*'));
        $this->assertNull(Cache::get('models.'.$model->id));
    }
}
