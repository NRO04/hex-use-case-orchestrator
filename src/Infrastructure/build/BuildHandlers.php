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

            $use_cases_built = [];

            $handler_class = $data_of_handler[$handlers_syntax['handler']];

            $use_cases = $data_of_handler[$handlers_syntax['use-cases']];

            $dependencies = $data_of_handler[$handlers_syntax['dependencies']];

            foreach ($data_of_handler[$handlers_syntax['compose']] as $use_case_name => $data_to_compose) {


                foreach ($data_to_compose as $dependency_ref => $use_case_ref) {

                    $this->handlers_syntax_compositions['use-cases']->validateKeyInComposition($use_case_ref, $use_cases, $handler_name);

                    $this->handlers_syntax_compositions['dependencies']->validateKeyInComposition($dependency_ref, $dependencies, $handler_name);

                    $use_cases_built[$use_case_name] = $this->buildClass->bind(
                        $this->buildClass->make($dependencies[$dependency_ref]), $use_cases[$use_case_ref]
                    );

                }

            }

            $handlers_built[$handler_name] = $this->buildClass->compose(
                $use_cases_built, $handler_class
            );

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