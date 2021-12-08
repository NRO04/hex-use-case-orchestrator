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

    function __construct(array $configuration)
    {
        $this->handlerCompositionApi = $configuration['handler-composition-api'];
        $this->reverseStructureOfTheComposition();
    }

    function getHandlerCompositionApi(): array
    {
        return $this->handlerCompositionApi;
    }

    function getTypeOfDataInCompositionWithAlias(string $alias_name): array
    {
        if (!$this->checkIfAliasExists($alias_name)) {
            throw new Exception("$alias_name not found in the handler-composition-api");
        }
        return $this->reverseHandlersComposition["$alias_name"];
    }

    function checkIfAliasExists(string $alias_name): bool
    {
        return array_key_exists($alias_name, $this->reverseHandlersComposition);
    }

    /**
     * @throws Exception
     */
    function execute(): void
    {
        foreach ($this->getHandlerCompositionApi() as $key => $handlerComposition) {
            if (!array_key_exists("$key", $this->baseHandlerComposition)) {
                throw new Exception("$key is not a valid for handler composition api");
            }
        }
    }

    function reverseStructureOfTheComposition(): void
    {
        foreach ($this->getHandlerCompositionApi() as $key_composition => $handlerComposition) {
            $this->reverseHandlersComposition[$handlerComposition["$key_composition"]] = $key_composition;
        }
    }

    /**
     * @throws Exception
     */
    function checkHandlerStructureComposition(string $property_to_validate): bool
    {
        if (!$this->checkIfAliasExists($property_to_validate)) {
            throw new Exception("$property_to_validate does not exits in handlers definition.");
        }
        return 1;
    }
}