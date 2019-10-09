<?php

namespace Orkhanahmadov\EloquentRepository\Tests\Repository;

use Orkhanahmadov\EloquentRepository\Tests\fixtures\ModelOne;
use Orkhanahmadov\EloquentRepository\Tests\fixtures\ModelTwo;
use Orkhanahmadov\EloquentRepository\Tests\TestCase;

class DeleteTest extends TestCase
{
    public function testDelete()
    {
        $model = ModelTwo::create(['id' => 1, 'name' => 'one']);
        $this->assertCount(1, ModelTwo::all());

        $result = $this->repository->entity(ModelTwo::class)->model($model)->delete();

        $this->assertTrue($result);
        $this->assertNull($model->fresh());
        $this->assertCount(0, ModelTwo::all());
    }

    public function testRestore()
    {
        $model = ModelOne::create(['id' => 1, 'name' => 'one']);
        $this->assertCount(1, ModelOne::all());
        $this->repository->entity(ModelOne::class)->model($model)->delete();
        $this->assertCount(0, ModelOne::all());

        $result = $this->repository->entity(ModelOne::class)->model($model)->restore();

        $this->assertTrue($result);
        $this->assertNotNull($model->fresh());
        $this->assertCount(1, ModelOne::all());
    }
}
