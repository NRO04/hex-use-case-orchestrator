<?php

namespace Ro\HexUseCaseOrchestrator\Domain\Repository;

use Ro\DtoPhp\Infrastructure\DTO;

interface UseCaseRepository
{
    function execute(DTO $dto = null): mixed;
}