<?php

namespace Ro\HexUseCaseOrchestrator\Infrastructure\Logs;

use Illuminate\Support\Facades\Log;
use Ro\HexUseCaseOrchestrator\Domain\Repository\UseCaseHandlersLogsRepository;

class UseCaseHandlersLogs implements UseCaseHandlersLogsRepository
{
    function write(string $message): void
    {
        Log::channel('use-case-handlers-logs')->info($message);
    }
}