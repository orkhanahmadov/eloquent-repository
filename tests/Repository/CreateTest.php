<?php

namespace Orkhanahmadov\EloquentRepository\Tests\Repository;

use Orkhanahmadov\EloquentRepository\Tests\fixtures\ModelOne;
use Orkhanahmadov\EloquentRepository\Tests\fixtures\ModelTwo;
use Orkhanahmadov\EloquentRepository\Tests\TestCase;

class CreateTest extends TestCase
{
    public function testCreatesModelWithBaseRepository()
    {
        $this->assertCount(0, ModelOne::all());

        $result = $this->repository->entity(ModelOne::class)->create(['id' => 14, 'name' => 'fourteen']);

        $this->assertNotNull($result);
        $this->assertSame(14, $result->id);
        $this->assertSame('fourteen', $result->name);
    }

    public function testCreatesRelation()
    {
        ModelOne::create(['id' => 1, 'name' => 'one']);
        $this->assertCount(1, ModelOne::all());
        $this->assertCount(0, ModelTwo::all());

        $result = $this->repository
            ->entity(ModelOne::class)
            ->relation('modelTwo')
            ->create(['id' => 3, 'name' => 'three']);

        $this->assertNotNull($result);
        $this->assertSame(3, $result->id);
        $this->assertSame('three', $result->name);
        $this->assertCount(1, ModelOne::all());
        $this->assertCount(1, ModelTwo::all());
    }

    public function testCreatesModelWithDedicatedRepository()
    {
        $this->assertCount(0, ModelOne::all());

        $result = $this->modelOneRepository->create(['id' => 16, 'name' => 'sixteen']);

        $this->assertNotNull($result);
        $this->assertSame(16, $result->id);
        $this->assertSame('sixteen', $result->name);
    }
}
