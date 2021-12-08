<?php

namespace Ro\HexUseCaseOrchestrator\Infrastructure\Composition;

use Ro\HexUseCaseOrchestrator\Domain\Repository\CompositionApiRepository;

class ValidateComposition implements CompositionApiRepository
{

    private array $classes;

    function __construct(array $compositions)
    {
        $this->classes = $compositions;
    }

    public function execute(): void
    {
        foreach ($this->classes as $class) {
            $class->execute();
        }
    }
}