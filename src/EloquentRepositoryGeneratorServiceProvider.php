<?php

namespace Innoscripta\EloquentRepositoryGenerator;

use Illuminate\Support\ServiceProvider;
use Innoscripta\EloquentRepositoryGenerator\Console\Commands\RepositoryMakeCommand;

class EloquentRepositoryGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('eloquent-repository-generator.php'),
            ], 'config');

            $this->commands([
                RepositoryMakeCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'eloquent-repository-generator');
    }
}
