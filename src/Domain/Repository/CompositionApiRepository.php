<?php

namespace Ro\HexUseCaseOrchestrator\Domain\Repository;

interface CompositionApiRepository
{
    function execute(): void;
}