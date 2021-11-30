<?php

namespace Ro\HexUseCaseOrchestrator\Domain\Repository;

use Ro\HexUseCaseOrchestrator\Domain\Contract\Schema\UseCaseHandlerSchema;

interface UseCaseOrchestratorRepository
{

    function getHandlers(): array;

    function get(string $handlerName): UseCaseHandlerSchema;

}