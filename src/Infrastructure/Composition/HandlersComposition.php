<?php

namespace Ro\HexUseCaseOrchestrator\Infrastructure\Composition;

use Exception;
use Ro\HexUseCaseOrchestrator\Domain\Repository\CompositionApiRepository;

class HandlersComposition implements CompositionApiRepository
{
    private array $baseHandlerComposition = [
        'handler' => 'required',
        'dependency' => 'required'
    ];
    private array $handlerCompositionApi;
    private array $reverseHandlersComposition; // reverse of handler composition api
    private array $handlers;

    /**
     * @throws Exception
     */
    function __construct(array $configuration)
    {
        $this->handlerCompositionApi = $configuration['handler-composition-api'];
        $this->handlers = $configuration['handlers'];
        $this->checkIfHandlersAreSet();
        $this->reverseStructureOfTheComposition();
    }

    function getHandlerCompositionApi(): array
    {
        return $this->handlerCompositionApi;
    }

    function getTypeOfDataInCompositionWithAlias(string $alias_name): string
    {
        return $this->reverseHandlersComposition["$alias_name"];
    }

    function checkIfAliasExists(string $alias_name): bool
    {
        return array_key_exists($alias_name, $this->reverseHandlersComposition);
    }

    /**
     * @throws Exception
     */
    function checkIfHandlersAreSet(): void
    {
        if (!isset($this->handlers)) {
            throw new Exception("There are not Handlers set, check handlers section in the configuration file use-case-orchestrator.php");
        }
    }

    /**
     * @throws Exception
     */
    function execute(): void
    {
        foreach ($this->getHandlerCompositionApi() as $key => $handlerComposition) {
            if (!array_key_exists("$key", $this->baseHandlerComposition)) {
                throw new Exception("$key is not a valid option for handler-composition-api, please check the configuration file use-case-orchestrator.php");
            }
        }
    }

    function reverseStructureOfTheComposition(): void
    {
        foreach ($this->getHandlerCompositionApi() as $key_composition => $handlerComposition) {
            $this->reverseHandlersComposition["$handlerComposition"] = $key_composition;
        }
    }

    /**
     * @throws Exception
     */
    function checkHandlerStructureComposition(string $property_to_validate): void
    {
        if (!$this->checkIfAliasExists($property_to_validate)) {
            throw new Exception("$property_to_validate is not nowhere to be found as an alias name in the handler-composition-api");
        }
    }
}