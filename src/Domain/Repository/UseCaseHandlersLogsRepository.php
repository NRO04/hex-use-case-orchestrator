<?php

namespace Ro\HexUseCaseOrchestrator\Domain\Repository;

interface UseCaseHandlersLogsRepository
{
    function write(string $message): void;
}