<?php

namespace Ro\HexUseCaseOrchestrator\Infrastructure\Composition;

use Ro\HexUseCaseOrchestrator\Domain\Repository\HandlerCompositionApiRepository;

class DependenciesComposition implements HandlerCompositionApiRepository
{
    /**
     * @throws \Exception
     */
    function execute($data): void
    {
        if (empty($data)) {
           throw new \Exception('There are not dependencies defined');
        }
    }

    /**
     * @throws \Exception
     */
    function validateKeyInComposition(string $key, array $composition, string $handler_name): void
    {
        if (!array_key_exists($key, $composition))  // Validates if the current key exists in the array to compare

            throw new \Exception("
            CONFLICTS IN THE BUILDING PROCESS: (Dependency name: $key ) is not found in the dependencies section of the handler: ($handler_name).
              If you see this message, check the compose section of the handler and verify that the dependency key is correct.");
    }

}