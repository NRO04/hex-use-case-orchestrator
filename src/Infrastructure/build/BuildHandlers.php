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
        $this->configurationComposition = new ConfigurationComposition($configuration);
        $this->handlersComposition = new HandlersComposition($configuration);

        $compositions = [
            $this->configurationComposition,
            $this->handlersComposition
        ];

        $this->validateComposition = new ValidateComposition($compositions);
        $this->execute();
    }

    /**
     * @throws \Exception
     */
    function execute(): void
    {
        $this->validateComposition->execute();
        $this->handlers = $this->configurationComposition->getConfigOption('handlers');
    }

    /**
     * Builds the handlers' composition.
     * @throws ReflectionException
     * @throws \Exception
     */
    function compose(): array
    {
        $handlers_composited = array();
        $dependency_class = ''; // the class which will be a dependency
        $handler_class = ''; // the class which will be an instance
        $class_priority = 1; // priority defines which class must be analyzed first.

        // iterate over the handlers defined in the configuration file in section 'handlers'
        foreach ($this->handlers as $handler_name => $handler_composition) {
            // iterate over the handler's composition [handler, dependency]
            foreach ($handler_composition as $alias => $class) {
                $this->handlersComposition->checkHandlerStructureComposition($alias);
                // resolve if is a dependency or a handler
                $type_data_composition = $this->handlersComposition->getTypeOfDataInCompositionWithAlias($alias);

                // validate which class must be used to build the handler using the priority.
                if ($this->handlersSyntax["$type_data_composition"]["priority"] === $class_priority) {
                    // if the priority is the same, then get the name of dynamic var
                    $dynamic_var = $this->handlersSyntax["$type_data_composition"]["dynamic_var"];
                    // overwrite the dynamic var with its value to assign the class.
                    //overwrite is necessary because using its value, the class will be an instance_class or a dependency_class
                    $$dynamic_var = $class;
                }
                // increment the priority to the next iteration
                $class_priority++;
            }
            $class_priority = 1; // reset the priority to the first iteration

            $handlers_composited["$handler_name"] = $this->bind(
            /*Creates a new instance of the dependency which defined in the config file.
             By default, this dependency it is call service in handler-composition-api section.
             Each handler is needs a dependency to be used.
             Handler's composition require a dependency and the provided service works like one.
             it will be a new instance of the service
            */
                $this->make($dependency_class),
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