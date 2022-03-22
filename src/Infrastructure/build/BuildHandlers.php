<?php

namespace Ro\HexUseCaseOrchestrator\Infrastructure\build;

use ReflectionException;
use Ro\HexUseCaseOrchestrator\Domain\Repository\CompositionApiRepository;
use Ro\HexUseCaseOrchestrator\Infrastructure\Composition\ComposeComposition;
use Ro\HexUseCaseOrchestrator\Infrastructure\Composition\ConfigurationComposition;
use Ro\HexUseCaseOrchestrator\Infrastructure\Composition\DependenciesComposition;
use Ro\HexUseCaseOrchestrator\Infrastructure\Composition\HandlerComposition;
use Ro\HexUseCaseOrchestrator\Infrastructure\Composition\HandlersComposition;
use Ro\HexUseCaseOrchestrator\Infrastructure\Composition\UseCaseComposition;
use Ro\HexUseCaseOrchestrator\Infrastructure\Composition\ValidateComposition;

class BuildHandlers implements CompositionApiRepository
{
    private ValidateComposition $validateComposition;

    private HandlersComposition $handlersComposition;

    private ConfigurationComposition $configurationComposition;

    private BuildClass $buildClass;

    private array $handlers_syntax_compositions = [];

    /**
     * @throws \Exception
     */
    function __construct(array $configuration)
    {
        $this->configurationComposition = new ConfigurationComposition($configuration);

        $this->handlersComposition = new HandlersComposition($configuration);

        $this->buildClass = new BuildClass();

        $compositions = [
            $this->configurationComposition,
            $this->handlersComposition
        ];

        $this->handlers_syntax_compositions = [
            'handler' => new HandlerComposition(),
            'dependencies' => new DependenciesComposition(),
            'use-cases' => new UseCaseComposition(),
            'compose' => new ComposeComposition()
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
    }

    /**
     * Builds the handlers' composition.
     * @throws ReflectionException
     * @throws \Exception
     */
    function compose(): array
    {
        $this->validateCompositionOfHandlersFromConfigFile();

        $handlers_built = [];

        $handlers_syntax = $this->handlersComposition->getHandlersSyntax();

        $handlers = $this->getHandlers();

        foreach ($handlers as $handler_name => $data_of_handler) {

            $use_cases = $data_of_handler[$handler_name][$handlers_syntax['use-cases']];

            $dependencies = $data_of_handler[$handler_name][$handlers_syntax['dependencies']];


            foreach ($data_of_handler[$handler_name][$handlers_syntax['compose']] as $dependency => $class) {

                $handlers_built[$handler_name] = $this->buildClass->bind(
                    $this->buildClass->make($dependencies[$dependency]), $use_cases[$class]
                );

            }

        }


        return $handlers_built;
    }

    /**
     * @throws \Exception
     */
    function validateCompositionOfHandlersFromConfigFile(): void
    {

        $handlers_syntax = $this->handlersComposition->getHandlersSyntax();

        $aliases_of_handlers_composition = $this->handlersComposition->reverseStructureOfTheComposition($handlers_syntax);

        $reverse_handler_syntax = $aliases_of_handlers_composition;

        $handlers = $this->getHandlers();

        while (!empty($handlers)) {

            $handler_name = array_key_last($handlers);

            $data_of_handler = $handlers[$handler_name];

            array_pop($handlers);

            foreach ($data_of_handler as $alias => $value) {

                $this->handlersComposition->validateKeyInComposition($alias, $aliases_of_handlers_composition);

                $key_handler_syntax = $reverse_handler_syntax[$alias];

                $this->handlers_syntax_compositions[$key_handler_syntax]->execute($value);

            }
        }
    }

    /**
     * @throws \Exception
     */
    function getHandlers(): array
    {
        return $this->configurationComposition->getConfigOption('handlers');
    }

}