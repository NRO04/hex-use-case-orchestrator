<?php

namespace Ro\HexUseCaseOrchestrator\Infrastructure\Collection;

use Ro\HexUseCaseOrchestrator\Domain\Contract\Schema\UseCaseHandlerSchema;
use Ro\HexUseCaseOrchestrator\Domain\Exceptions\UseCaseHandlerNotFound;


class UseCaseHandlerCollection
{
    /** @var array<string, UseCaseHandlerSchema > */
    public array $handlers = [];

    /**
     * @param string $handlerName
     * @return UseCaseHandlerSchema
     */
    function getHandler(string $handlerName): UseCaseHandlerSchema
    {
        if (!$this->checkIfHandlerExists($handlerName))
            throw new UseCaseHandlerNotFound("Handler: $handlerName not found, please check your use case configuration, config/use-case-handlers.php");

        return $this->handlers[$handlerName];
    }

    /** @var array<string, UseCaseHandlerSchema> */
    public function add(array $handlers): void
    {
        $this->handlers = $handlers;
    }

    function checkIfHandlerExists(string $handlerName): bool
    {
        return isset($this->handlers[$handlerName]);
    }

}