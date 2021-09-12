<?php

namespace Pkeogan;

use App;
use Config;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Support\Str;
use Pkeogan\Commands\DemoCommand;
use Pkeogan\Commands\ErrorsCommand;
use Pkeogan\Commands\DocumentationCommand;
use Pkeogan\Commands\ListAPICommand;
use Pkeogan\Commands\RelationshipsDemo;
use Pkeogan\Factories\LaravelFileFactory;
use Pkeogan\Schema\LaravelSchema;
use Pkeogan\Factories\PHPFileFactory;
use Pkeogan\Factories\ProjectFactory;
use Pkeogan\Traits\AddsLaravelStringsToStrWithMacros;

class ServiceProvider extends BaseServiceProvider
{
    use AddsLaravelStringsToStrWithMacros;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        dd('hi');
        $this->registerFacades();
        $this->registerCommands();
        $this->mergeConfigFrom(__DIR__.'/config/Pkeogan.php', 'Pkeogan');
    }

    public function boot()
    {
        $this->bootStrMacros();
        $this->publishConfig();
    }

    protected function registerFacades()
    {
        App::bind('PHPFile', function () {
            return app()->make(PHPFileFactory::class);
        });

        App::bind('LaravelFile', function () {
            return app()->make(LaravelFileFactory::class);
        });

        App::bind('Pkeogan\Facades\Project', function () {
            return app()->make(ProjectFactory::class);
        });
    }

    protected function publishConfig()
    {
        $this->publishes([
            __DIR__.'/config/Pkeogan.php' => config_path('Pkeogan.php'),
        ]);
    }

    protected function registerCommands()
    {
        $this->commands([
            ListAPICommand::class,
            DemoCommand::class,
            RelationshipsDemo::class,
            ErrorsCommand::class,
            DocumentationCommand::class,
        ]);
    }
}
