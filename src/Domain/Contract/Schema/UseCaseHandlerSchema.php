<?php

namespace Ro\HexUseCaseOrchestrator\Domain\Contract\Schema;

use Ro\HexUseCaseOrchestrator\Domain\Repository\UseCaseRepository;

interface UseCaseHandlerSchema
{
    function getUseCase(string $useCaseName): UseCaseRepository;

    function getUseCaseList(): array;

    function getUseCaseNamesList(): array;

    function checkIfUseCaseExists(string $useCaseName): bool;

    function addUseCase(string $useCaseName, string $useCaseClass): void;

    function removeUseCase(string $useCaseName): void;

    function addMultipleUseCase(array $useCaseList): void;
}