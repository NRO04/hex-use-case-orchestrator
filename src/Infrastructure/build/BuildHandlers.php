<?php

namespace Ro\HexUseCaseOrchestrator\Infrastructure\build;

use ReflectionException;
use Ro\HexUseCaseOrchestrator\Domain\Repository\CompositionApiRepository;
use Ro\HexUseCaseOrchestrator\Infrastructure\Composition\ValidateComposition;

class BuildHandlers implements CompositionApiRepository
{
    private ValidateComposition $validateComposition;
    private array $handlers;

    function __construct(array $configuration)
    {
        $this->validateComposition = new ValidateComposition($configuration);
        $this->handlers = $this->validateComposition->getHandlers();
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
     * @throws \Exception
     */
    function checkHandlerCompositionApi($composition_key, $key_to_validate): void
    {
        if (!$composition["$composition_key"]["$key_to_validate"]) {
            throw (new \Exception("The $key_to_validate is not defined"));
        }
    }

    function validateHandlerComposition(array $handlers_composition): void
    {
        $this->composition = $handlers_composition;

        foreach ($this->composition as $composition_key => $composition_value) {
            $this->checkComposition($value_handler, "service");
        }
    }

    /**
     * Binds a handler with a dependency, and returns a new instance of the handler with the dependency injected.
     * @throws ReflectionException
     */
    function bind(object $dependency, $handler): object
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