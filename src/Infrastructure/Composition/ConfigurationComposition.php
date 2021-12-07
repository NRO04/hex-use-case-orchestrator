<?php

namespace Ro\HexUseCaseOrchestrator\Infrastructure\Composition;

use Exception;

class ConfigurationComposition
{
    private array $baseApiConfiguration = [
        'api-composition' => 'required',
        'handlers' => 'required',
        'logs' => 'required'
    ];

    private array $apiCompositionConfiguration = [];

    function __construct(array $api_composition_configuration)
    {
        $this->apiCompositionConfiguration = $api_composition_configuration;
    }

    /**
     * @throws Exception
     */
    function execute()
    {
        foreach ($this->apiCompositionConfiguration as $config_option => $value_option) {
            if (!array_key_exists($config_option, $this->baseApiConfiguration)) {
                throw new Exception("The configuration option '$config_option' is not supported");
            }
        }
    }


}