<?php

namespace Orkhanahmadov\EloquentRepository\Tests\Console;

use InvalidArgumentException;
use Orkhanahmadov\EloquentRepository\Tests\TestCase;

class RepositoryMakeCommandTest extends TestCase
{
    public function testCreatesRepositoryWithoutModel()
    {
        $this->artisan('make:repository', ['name' => 'FooRepository'])
            ->assertExitCode(0);

        $this->assertTrue(is_file(app_path() . '/Repositories/FooRepository.php'));
    }

    public function testCreatesRepositoryWithModel()
    {
        $this->artisan('make:repository', ['name' => 'FooModelRepository', '--model' => 'FooModel'])
            ->expectsQuestion('A App\FooModel model does not exist. Do you want to generate it?', 'yes')
            ->assertExitCode(0);

        $this->assertTrue(is_file(app_path() . '/Repositories/FooModelRepository.php'));
    }

    public function testThrowsExceptionWhenInvalidModelNameSpecified()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Model name contains invalid characters.');

        $this->artisan('make:repository', ['name' => 'FooModelRepository', '--model' => 'FooModel!'])
            ->assertExitCode(1);
    }

    protected function tearDown(): void
    {
        if (file_exists(app_path() . '/Repositories/FooRepository.php')) {
            unlink(app_path() . '/Repositories/FooRepository.php');
        }

        if (file_exists(app_path() . '/Repositories/FooModelRepository.php')) {
            unlink(app_path() . '/Repositories/FooModelRepository.php');
            unlink(app_path() . '/FooModel.php');
        }

        parent::tearDown();
    }
}
