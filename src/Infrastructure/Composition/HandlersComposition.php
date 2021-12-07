<?php

namespace Ro\HexUseCaseOrchestrator\Infrastructure\Composition;

use Exception;
use ReflectionClass;
use ReflectionException;
use Ro\HexUseCaseOrchestrator\Domain\Repository\CompositionApiRepository;

class HandlersComposition implements CompositionApiRepository
{
    private array $baseHandlerComposition = [
        'handler' => 'required',
        'dependency' => 'required'
    ];
    private array $handlerCompositionApi;

    function __construct(array $configuration)
    {
        $this->handlerCompositionApi = $configuration['handler-composition-api'];
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
}