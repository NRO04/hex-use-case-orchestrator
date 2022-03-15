<?php

namespace Ro\HexUseCaseOrchestrator\Infrastructure\ServiceProvider;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Ro\HexUseCaseOrchestrator\Infrastructure\build\BuildHandlers;
use Ro\HexUseCaseOrchestrator\Infrastructure\Orchestrator\UseCaseOrchestrator;

define('HEX_USE_CASE_HANDLERS_PATH', __DIR__ . '/../../../app/UseCases/Handlers.php');
define('HEX_USE_CASE_HANDLER_CONFIG_PATH', __DIR__ . '/../../../config/use-case-handlers.php');
define('HEX_USE_CASE_HANDLER_LOGS_PATH', __DIR__ . '/../../../logs/use-case-handlers.log');

class UseCaseOrchestratorServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Merge Use Case Handlers config in to app config
        $this->mergeConfigFrom(
            $this->configPath(), 'use-case-handlers'
        );

        /* Add the UseCaseOrchestrator to the Service container,
        then it is possible use it with Dependency Injection, The UseCaseOrchestrator
        works with Dependency Inversion principle. So, it is possible to inject classes that implement
        useCaseHandlerSchemaInterface.
        */
        $this->app->singleton(
            UseCaseOrchestrator::class,
            function ($app) {
                return new UseCaseOrchestrator(
                    (new BuildHandlers(config('use-case-handlers')))->compose()
                );
            }
        );
    }

    public function boot()
    {
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {

            $this->publishes([
                /* Publish the config file */
                $this->configPath() => config_path('use-case-handlers.php'),

                /* Publish the log file */
                $this->logsPath() => storage_path(config('use-case-handlers.logs.path')),
            ], 'use-case-handlers-config');
        }
    }

    /**
     * Get the path to the use case handler config file.
     * @return string
     */
    function configPath(): string
    {
        return HEX_USE_CASE_HANDLER_CONFIG_PATH;
    }

    /**
     * Get the path to the use case handlers.
     * The handlers must be in the app/UseCases/Handlers.php file.
     * @return string
     */
    function basePath(): string
    {
        return HEX_USE_CASE_HANDLERS_PATH;
    }

    /**
     * Get the path to the use case handlers logs.
     * @return string
     */
    function logsPath(): string
    {
        return HEX_USE_CASE_HANDLER_LOGS_PATH;
    }
}