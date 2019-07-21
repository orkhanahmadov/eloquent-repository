<?php

namespace Innoscripta\EloquentRepository\Tests\Console;

use Innoscripta\EloquentRepository\Tests\TestCase;

class RepositoryMakeCommandTest extends TestCase
{
    public function testCreatesRepositoryWithoutModel()
    {
        $this->artisan('make:repository FooRepository')
            ->assertExitCode(0);
    }

    public function testCreatesRepositoryWithModel()
    {
        $this->artisan('make:repository FooRepository --model=Innoscripta/EloquentRepository/Tests/Model')
            ->assertExitCode(0);
    }
}
