<?php

namespace Ro\HexUseCaseOrchestrator\Domain\Repository;

use Ro\HexUseCaseOrchestrator\Domain\Contract\Schema\UseCaseHandlerSchema;

interface UseCaseOrchestratorRepository
{

    /* Get all handlers that are already registered in the handlersCollection */
    function getHandlers(): array;

    /* Get a specific handler by its name */
    function get(string $handlerName): UseCaseHandlerSchema;

}