<?php

namespace Ro\HexUseCaseOrchestrator\Infrastructure\Collection;

use Ro\HexUseCaseOrchestrator\Domain\Contract\Schema\UseCaseHandlerSchema;
use Ro\HexUseCaseOrchestrator\Domain\Exceptions\UseCaseHandlerNotFound;
use Ro\HexUseCaseOrchestrator\Infrastructure\Logs\UseCaseHandlersLogs;

class UseCaseHandlerCollection
{
    protected UseCaseHandlersLogs $logs;
    /** @var array<string, UseCaseHandlerSchema > */
    public array $handlers = [];

    function __construct()
    {
        $this->logs = new UseCaseHandlersLogs();
    }

    /**
     * @param string $handlerName
     */
    function getHandler(string $handlerName): UseCaseHandlerSchema
    {
        if (!$this->checkIfHandlerExists($handlerName)) {

            $message = "Handler: << {$handlerName} >> was not found, please check your use case configuration in
             config/use-case-handlers.php and verify that the << $handlerName >> is registered in the handlers section";
            $this->logs->write($message);
            throw new UseCaseHandlerNotFound($message);
        }

        return $this->handlers[$handlerName];
    }

    /** @var array<string, UseCaseHandlerSchema> */
    public function add(array $handlers): void
    {
        $this->handlers = $handlers;
    }

    function checkIfHandlerExists(string $handlerName): bool
    {
        return array_key_exists($handlerName, $this->handlers);
    }

}