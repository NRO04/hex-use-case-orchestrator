<?php

namespace Ro\HexUseCaseOrchestrator\Domain\Repository;

interface HandlerCompositionApiRepository
{

    function execute($data): void;
}