<?php

namespace Ro\HexUseCaseOrchestrator\Infrastructure\Composition;

use Exception;
use Ro\HexUseCaseOrchestrator\Domain\Repository\CompositionApiRepository;

class ConfigurationComposition implements CompositionApiRepository
{
    private array $baseConfigurationApi = [
        'handler-composition-api' => 'required',
        'handlers' => 'required',
        'logs' => 'required'
    ];

    private array $configurationApiComposition = [];

    function __construct(array $configuration_api_composition)
    {
        $this->configurationApiComposition = $configuration_api_composition;
    }

    public function getConfigurationCompositionApi(): array
    {
        return $this->configurationApiComposition;
    }

    /**
     * @throws Exception
     */
    function execute(): void
    {
        foreach ($this->getConfigurationCompositionApi() as $config_option => $value_option) {
            if (!array_key_exists($config_option, $this->baseConfigurationApi)) {
                throw new Exception("The option: '$config_option' is not valid");
            }
        }
    }


}