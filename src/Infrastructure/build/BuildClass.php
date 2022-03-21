<?php

namespace Ro\HexUseCaseOrchestrator\Infrastructure\build;

class BuildClass
{

    public function __construct()
    {
    }

    /**
     * Binds a class with a dependency, and returns a new instance of the class with the dependency injected.
     * @throws \ReflectionException
     */
    function bind(object $dependency, string $class): object
    {
        $instance = new \ReflectionClass($class);
        return $instance->newInstance($dependency);
    }

    /**
     * Create new instance of provided class using reflection
     * @throws \ReflectionException
     */
    function make($abstract): object
    {
        $instance = new \ReflectionClass($abstract);
        return $instance->newInstance();
    }

}