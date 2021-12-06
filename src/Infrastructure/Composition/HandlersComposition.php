<?php

namespace Ro\HexUseCaseOrchestrator\Infrastructure\Composition;

use ReflectionClass;
use ReflectionException;

class HandlersComposition
{

    /**
     * Builds the handlers' composition.
     * @throws ReflectionException
     */
    function compose(array $handlers): array
    {
        $handlers_composited = array();
        foreach ($handlers as $handler_name => $handler) {
            $handlers_composited["$handler_name"] = $this->bind(

            //Creates a new instance of the dependency which defined in the config file. By default, this dependency it is call service
            //Each handler is needs a service to be executed.
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