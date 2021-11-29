<?php

namespace Ro\HexUseCaseHandler\Domain\Repository;

use Ro\DtoPhp\Infrastructure\DTO;

interface UseCaseRepository
{
    function execute(DTO $dto = null): mixed;
}