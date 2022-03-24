<?php

namespace Ro\HexUseCaseOrchestrator\Infrastructure\Composition;

use Exception;
use Ro\HexUseCaseOrchestrator\Domain\Repository\CompositionApiRepository;

class HandlersComposition implements CompositionApiRepository
{
    private array $baseHandlerComposition = [
        'resolve' => [

            'alias' => ["dependencies", "use-cases", "handler", "compose"]
        ]
    ];
    private array $handlerCompositionApi;
    private array $reverseHandlersComposition; // reverse of handler composition api
    private array $handlers;
    private array $configuration_file_data;

    /**
     * @throws Exception
     */
    function __construct(array $configuration)
    {
        $this->handlerCompositionApi = $configuration['handler-composition-api'];
        $this->handlers = $configuration['handlers'];
        $this->configuration_file_data = $configuration;
    }

    function getHandlerCompositionApi(): array
    {
        return $this->handlerCompositionApi;
    }

    function getBaseCompositionOfHandler(): array
    {
        return $this->baseHandlerComposition;
    }

    function getHandlersSyntax(): array
    {
        return $this->extractor("alias", $this->handlerCompositionApi);
    }


    /**
     * @throws Exception
     */
    function checkIfHandlersAreSet(): void
    {
        if (empty($this->handlers)) {
            throw new Exception("There are not Handlers set, check handlers section in the configuration file use-case-orchestrator.php");
        }
    }

    /**
     * @throws Exception
     */
    function execute(): void
    {

        if (!$this->configuration_file_data['allow_empty-handlers']) {

            $this->checkIfHandlersAreSet();

            $base_composition = $this->getHandlerCompositionFrom('base-composition');

            $file_composition = $this->getHandlerCompositionFrom('configuration-file');

            $this->validateComposition($base_composition, $file_composition);
        }
    }

    function reverseStructureOfTheComposition(array $data_to_reverse): array
    {
        $reverse_composition = [];
        foreach ($data_to_reverse as $key_composition => $handlerComposition) {
            $reverse_composition["$handlerComposition"] = $key_composition;
        }
        return $reverse_composition;
    }

    /**
     * @param string $source
     * @return array
     * @throws Exception
     *   Gets array from a specific source, helps to obtain any composition recorded in the compositions records
     */
    function getHandlerCompositionFrom(string $source): array
    {

        // Composition Records
        $compositions = [
            'configuration-file' => $this->getHandlerCompositionApi(),
            'base-composition' => $this->getBaseCompositionOfHandler()
        ];

        if (!array_key_exists($source, $compositions)) throw new Exception("Unable to find the source: $source in the compositions records");

        return $compositions[$source];
    }

    function isSequentialArray(array $data): bool
    {

        return array_values($data) === $data; // Uses the comparison operator to validate if both are of the same type in this case both must be a sequential array.
    }


    /**
     * @throws Exception
     */
    function validateKeyInComposition(string $key, array $composition): void
    {

        if (!array_key_exists($key, $composition))  // Validates if the current key exists in the array to compare

            throw new Exception("Required key: ($key) not found in the validation process, please check that your api structure is correct");

    }

    /**
     * @throws Exception
     */
    function validateComposition(array $base_composition, array $compare): void
    {

        while (!empty($base_composition)) { //Loop until the array is empty

            // --------------------------------STEP 1---------------------------------------------------

            $key_base_composition = array_key_last($base_composition); // Gets the last key of the array

            $pop_item_base_composition = $base_composition[$key_base_composition]; // Gets the last item in the array

            array_pop($base_composition); // Deletes the last item of the array


            $this->validateKeyInComposition($key_base_composition, $compare); // Validates if the current key exists in the array to compare


            // ---------------------------------STEP 2--------------------------------------------------

            $key_compare = $key_base_composition; // Gets the current key in compare array

            $pop_item_compare = $compare[$key_compare]; // Gets the last item in compare array


            // ---------------------------------STEP 3--------------------------------------------------


            if (!$this->isSequentialArray($pop_item_base_composition))  //validates if is assoc array

                $this->validateComposition($pop_item_base_composition, $pop_item_compare); // Calling itself again but now with its internal values, means that it will send values that are assoc array to validate again and again until there are no more assoc array elements.

            else

                foreach ($pop_item_base_composition as $composition_value) { // Loop when the array is sequential, this will iterate each position validating that the key exists in the compare array

                    $this->validateKeyInComposition($composition_value, $pop_item_compare); // Validates whether the current value of the sequential array exists as a key in the array to be compared.

                }

        }
    }

    /**
     * Searches and extracts an element from the array specified by the key
     * @param string $key The key to be extracted
     * @param array $data The array to be searched
     * @return array The extracted element
     */
    function extractor(string $key, array $data): array
    {
        $result = [];

        while (!empty($data)) {

            $key_data = array_key_last($data);
            $pop_item_data = $data[$key_data];

            array_pop($data);

            if ($key_data === $key) {
                $result = $pop_item_data;
                break;
            }

            if (!$this->isSequentialArray($pop_item_data)) {

                $data = $data + $pop_item_data; // Merges the two arrays using array operator (union).
            }
        }
        return $result;
    }

}