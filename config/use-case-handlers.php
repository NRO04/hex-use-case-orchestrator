<?php

return [
    /*
  |--------------------------------------------------------------------------
  | Use Case Handlers configuration  (Nicolas RO)
  |--------------------------------------------------------------------------
  |   Put here the configuration for the use case handlers.
  |   Here are the paths to the use case handlers.
  |   By default, the handlers are in app/UseCases/Handlers.
  |   You can add as many use case handlers as you want in that folder.
  |   If you want to create a new use case handler, create a new class that implements UseCaseHandlerSchema
  */

    'handlers' => [
        /*
         * Example:
        |--------------------------------------------------------------------------
        'put-here-handler-name' =>[
            '@handler' => UserUseCaseHandler::class, //key @handler is reserved
            '@service' => UserService::class, //key @service is reserved
        ],
         */
    ],
    'logs' => [
        'path' => 'logs/use-case-handlers.log',
    ]
];
