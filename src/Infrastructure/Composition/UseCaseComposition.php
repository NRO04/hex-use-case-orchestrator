<?php

namespace Ro\HexUseCaseOrchestrator\Infrastructure\Composition;

use Ro\HexUseCaseOrchestrator\Domain\Repository\HandlerCompositionApiRepository;

class UseCaseComposition implements HandlerCompositionApiRepository
{
    /**
     * @throws \Exception
     */
    function execute($data): void
    {
        if (empty($data)) {
            throw new \Exception('There are not uses cases defined');
        }
    }

}