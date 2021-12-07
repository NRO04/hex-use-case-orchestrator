<?php

namespace Ro\HexUseCaseOrchestrator\Infrastructure\Composition;

use Ro\HexUseCaseOrchestrator\Domain\Repository\CompositionApiRepository;

class ValidateComposition implements CompositionApiRepository
{

    private array $classes;

    function __construct(array $configuration)
    {
        $this->classes = [
            new ConfigurationComposition($configuration),
            new HandlersComposition($configuration)
        ];
    }

    public function execute(): void
    {
        foreach ($this->classes as $class) {
            $class->execute();
        }
    }
}