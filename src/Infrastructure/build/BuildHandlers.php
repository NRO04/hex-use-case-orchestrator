<?php

namespace Ro\HexUseCaseOrchestrator\Infrastructure\build;

use ReflectionException;
use Ro\HexUseCaseOrchestrator\Domain\Repository\CompositionApiRepository;
use Ro\HexUseCaseOrchestrator\Infrastructure\Composition\ConfigurationComposition;
use Ro\HexUseCaseOrchestrator\Infrastructure\Composition\HandlersComposition;
use Ro\HexUseCaseOrchestrator\Infrastructure\Composition\ValidateComposition;

class BuildHandlers implements CompositionApiRepository
{
    private ValidateComposition $validateComposition;
    private array $handlers;
    private HandlersComposition $handlersComposition;
    private ConfigurationComposition $configurationComposition;
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

    /**
     * @throws \Exception
     */
    function __construct(array $configuration)
    {
        $this->handlersComposition = new HandlersComposition($configuration);
        $this->configurationComposition = new ConfigurationComposition($configuration);

        $compositions = [
            $this->handlersComposition,
            $this->configurationComposition
        ];

        $this->validateComposition = new ValidateComposition($compositions);
        $this->execute();
    }

    /**
     * @throws \Exception
     */
    function boot()
    {
        $this->handlers = $this->configurationComposition->getConfigOption('handlers');

    }

    function execute(): void
    {
        $this->validateComposition->execute();
    }

    /**
     * Builds the handlers' composition.
     * @throws ReflectionException
     * @throws \Exception
     */
    function compose(): array
    {
        $handlers_composited = array();
        $dependency_class = '';
        $handler_class = '';
        $priority = 1;
        // iterate over the handlers defined in the configuration file in section 'handlers'
        foreach ($this->handlers as $handler_name => $handler_composition) {
            // iterate over the handler's composition [handler, dependency]
            foreach ($handler_composition as $alias => $class) {
                $this->handlersComposition->checkHandlerStructureComposition($alias);
                // resolve if is a dependency or a handler
                $type_data_composition = $this->handlersComposition->getTypeOfDataInCompositionWithAlias($alias);

                if ($this->handlersSyntax["$type_data_composition"]["priority"] == $priority) {
                    $dynamic_var = $this->handlersSyntax["$type_data_composition"]["dynamic_var"];
                    $$dynamic_var = $class;
                }
                $priority++;
            }

            $handlers_composited["$handler_name"] = $this->bind(

            //Creates a new instance of the dependency which defined in the config file. By default, this dependency it is call service
            //Each handler is needs a dependency to be used.
            // Handler's composition require a dependency and the provided service works like one.
            // it will be a new instance of the service
                $this->make($dependency_class),
                //Get handlers class
                $handler_class
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