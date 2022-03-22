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

    /**
     * @throws \Exception
     */
    function validateKeyInComposition($key, $composition, $handler_name): void
    {
        if (!array_key_exists($key, $composition))  // Validates if the current key exists in the array to compare

            throw new \Exception("CONFLICTS IN THE BUILDING PROCESS: (Use Case name: $key ) is not found in the use case section of the handler: ($handler_name).
              If you see this message, check the compose section of the handler and verify that the use case key is correct.");

    }

}