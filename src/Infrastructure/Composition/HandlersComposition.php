<?php

namespace Ro\HexUseCaseOrchestrator\Infrastructure\Composition;

use ReflectionClass;
use ReflectionException;

class HandlersComposition
{

    private array $handlers;
    private array $composition;
    private array $configuration;


    function __construct(array $configuration)
    {
        $this->configuration = $configuration;
        $this->handlers = $configuration['handlers'];
        $this->composition = $configuration['api-composition'];
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
    function checkComposition($composition_key, $key_to_validate): void
    {
        if (!$composition["$composition_key"]["$key_to_validate"]) {
            throw (new \Exception("The $key_to_validate is not defined"));
        }
    }

    /**
     * @throws \Exception
     */
    function checkConfiguration(): void
    {
        foreach ($this->configuration as $config_option => $config_value) {

            if (!array_key_exists("$config_option", $this->baseConfiguration)) {
                throw (new \Exception("The $config_option is not configuration option"));
            }

            if ($config_option == "handlers") {
                $this->validateHandlerComposition($config_value);
            }
        }
    }

    function validateHandlerComposition(array $handlers_composition): void
    {
        $this->composition = $handlers_composition;

        foreach($this->composition as $composition_key => $composition_value){
            $this->checkComposition($value_handler, "service");
        }
    }

    /**
     * Binds a handler with a dependency, and returns a new instance of the handler with the dependency injected.
     * @throws ReflectionException
     */
    function bind(object $dependency, $handler): object
    {
        $instance = new ReflectionClass($handler);
        return $instance->newInstance($dependency);
    }

    /**
     * Create new instance of provided class using reflection
     * @throws ReflectionException
     */
    function make($abstract): object
    {
        $instance = new ReflectionClass($abstract);
        return $instance->newInstance();
    }
}