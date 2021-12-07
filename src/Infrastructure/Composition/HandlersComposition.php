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
    function checkStructureCompositionApi(string $property_to_validate): bool
    {
        if (!array_key_exists("$property_to_validate", $this->reverseHandlersComposition)) {
            throw new Exception("$property_to_validate does not exits in handlers definition.");
        }
        return 1;
    }
}