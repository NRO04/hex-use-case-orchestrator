<?php

namespace Ro\HexUseCaseOrchestrator\Infrastructure\Composition;

use Exception;
use Ro\HexUseCaseOrchestrator\Domain\Repository\CompositionApiRepository;

/**
 * Class ConfigurationComposition
 * Validates the configuration composition from the configuration file
 */
class ConfigurationComposition implements CompositionApiRepository
{
    /**
     * Contains the base configuration composition
     */
    private array $baseConfigurationApi = [
        'handler-composition-api' => 'required',
        'handlers' => 'required',
        'logs' => 'required'
    ];

    /**
     * Contains the configuration composition that has the configuration file.
     */
    private array $configurationApiComposition = [];

    /**
     * @throws Exception
     */
    function __construct(array $configuration_api_composition)
    {
        $this->configurationApiComposition = $configuration_api_composition;
        $this->execute();
    }
    
    /**
     * Gets the configuration composition from the configuration file
     */
    function getConfigurationCompositionApi(): array
    {
        return $this->configurationApiComposition;
    }

    function getConfigKeys(): array
    {
        return array_keys($this->baseConfigurationApi);
    }

    /**
     * @throws Exception
     */
    function getConfigOption(string $key): array
    {
        if (!array_key_exists($key, $this->configurationApiComposition)) {
            throw new Exception("Key {$key} not found in configuration");
        }
        return $this->configurationApiComposition[$key];
    }

    /**
     * @throws Exception
     */
    function execute(): void
    {
        foreach ($this->getConfigurationCompositionApi() as $config_option => $value_option) {
            if (!array_key_exists($config_option, $this->baseConfigurationApi)) {
                throw new Exception("The option: '$config_option' is not a valid option, please check the config file");
            }
        }
    }
}