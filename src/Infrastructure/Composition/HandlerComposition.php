<?php

namespace Ro\HexUseCaseOrchestrator\Infrastructure\Composition;

use Ro\HexUseCaseOrchestrator\Domain\Repository\HandlerCompositionApiRepository;

class HandlerComposition implements HandlerCompositionApiRepository
{
    /**
     * @throws \Exception
     */
    function execute($data): void
    {
        if (empty($data)) {
            throw new \Exception('There is not handler defined');
        }
    }

}