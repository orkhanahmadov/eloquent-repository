<?php

namespace Innoscripta\EloquentRepository\Tests\Console;

use Innoscripta\EloquentRepository\Tests\TestCase;

class RepositoryMakeCommandTest extends TestCase
{
    public function testCreatesRepositoryWithoutModel()
    {
        $res = $this->artisan('make:repository App/Something/FooRepository')->execute();


    }
}
