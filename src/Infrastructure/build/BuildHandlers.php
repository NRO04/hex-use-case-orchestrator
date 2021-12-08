<?php

namespace Ro\HexUseCaseOrchestrator\Infrastructure\build;

use ReflectionException;
use Ro\HexUseCaseOrchestrator\Domain\Repository\CompositionApiRepository;
use Ro\HexUseCaseOrchestrator\Infrastructure\Composition\ConfigurationComposition;
use Ro\HexUseCaseOrchestrator\Infrastructure\Composition\ValidateComposition;

class BuildHandlers implements CompositionApiRepository
{
    private ValidateComposition $validateComposition;
    private array $handlers;
    private ConfigurationComposition $configuration;
    private array $handlersSyntax = [
        'handler' => [
            'priority' => 1, // priority defines which class will be an instance and which will be a dependency
            'dynamic_var' => 'handler_class', // dynamic_var is the name of the var which will save the class
        ],
        'dependency' => [
            'priority' => 2,
            'dynamic_var' => 'dependency_class',
        ],
    ];

    function __construct(array $configuration)
    {
        $this->validateComposition = new ValidateComposition($configuration);
        $this->configuration = new ConfigurationComposition($configuration);
        $this->execute();
    }

    /**
     * @throws \Exception
     */
    function boot()
    {
        $this->handlers = $this->configuration->getConfigOption('handlers');

    }

    function execute(): void
    {
        $this->validateComposition->execute();
    }

    /**
     * Builds the handlers' composition.
     * @throws ReflectionException
     */
    function compose(): array
    {
        $handlers_composited = array();
        $dependency = '';
        $handler_class = '';
        foreach ($this->handlers as $handler_name => $handler) {
            $handlers_composited["$handler_name"] = $this->bind(

            //Creates a new instance of the dependency which defined in the config file. By default, this dependency it is call service
            //Each handler is needs a dependency to be used.
            // Handler's composition require a dependency and the provided service works like one.
            // it will be a new instance of the service
                $this->make($handler["$handler_name"]["@service"]),
                //Get handlers class
                $handler["$handler_name"]["@handler"]
            );
        }
        return $handlers_composited;
    }
    /**
     * Binds a handler with a dependency, and returns a new instance of the handler with the dependency injected.
     * @throws ReflectionException
     */
    function bind(object $dependency, string $handler): object
    {
        $instance = new \ReflectionClass($handler);
        return $instance->newInstance($dependency);
    }

    /**
     * Create new instance of provided class using reflection
     * @throws ReflectionException
     */
    function make($abstract): object
    {
        $instance = new \ReflectionClass($abstract);
        return $instance->newInstance();
    }

}