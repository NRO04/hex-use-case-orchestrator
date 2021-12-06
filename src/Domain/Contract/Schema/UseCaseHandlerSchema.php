<?php

namespace Ro\HexUseCaseOrchestrator\Domain\Contract\Schema;

use Ro\HexUseCaseOrchestrator\Domain\Repository\UseCaseRepository;

interface UseCaseHandlerSchema
{
    function getUseCase(string $useCaseName): UseCaseRepository;

    function getUseCaseList(string $useCaseName): array;

    function checkIfUseCaseExists(string $useCaseName): bool;

}