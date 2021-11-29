<?php

namespace Ro\HexUseCaseHandler\Domain\Contract\Schema;

interface UseCaseHandlerSchema
{
    function getUseCase(string $useCaseName): mixed;

    function getUseCaseList(string $useCaseName): array;

    function checkIfUseCaseExists(string $useCaseName): bool;

}