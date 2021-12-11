<?php

namespace Ro\HexUseCaseOrchestrator\Infrastructure\Orchestrator;

use Ro\HexUseCaseOrchestrator\Domain\Contract\Schema\UseCaseHandlerSchema;
use Ro\HexUseCaseOrchestrator\Domain\Repository\UseCaseOrchestratorRepository;
use Ro\HexUseCaseOrchestrator\Infrastructure\Collection\UseCaseHandlerCollection;

class UseCaseOrchestrator implements UseCaseOrchestratorRepository
{

    protected UseCaseHandlerCollection $useCaseHandlerCollection;

    /** @var array<string, UseCaseHandlerSchema> */
    public function __construct(array $handlers)
    {
        $this->useCaseHandlerCollection = new UseCaseHandlerCollection();
        $this->register($handlers);
    }

    /** @var array<string, UseCaseHandlerSchema> */
    function register(array $handlers)
    {
        $this->useCaseHandlerCollection->add($handlers);
    }

    function get(string $handlerName): ?UseCaseHandlerSchema
    {
        return $this->useCaseHandlerCollection->getHandler($handlerName);
    }

    function getsHandlersNamesList(): array
    {
        return array_keys($this->useCaseHandlerCollection->handlers);
    }

    function getHandlers(): array
    {
        return $this->useCaseHandlerCollection->handlers;
    }
}