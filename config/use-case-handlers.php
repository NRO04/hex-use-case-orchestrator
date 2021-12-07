<?php

return [
    /*
  |--------------------------------------------------------------------------
  | Use Case Handlers configuration  (Nicolas RO)
  |--------------------------------------------------------------------------
  |   Put here the configuration for the use case handlers.
  |   You can add as many use case handlers as you want in handlers section.
  |   If you want to create a new use case handler, you must create a new class that implements UseCaseHandlerSchema
       Remember to add the new class to the handlers' section for the use case handler to be used.

  */

    'handlers' => [
        /*
         Example:
        'put-here-handler-name' =>[
            '@handler' => UserUseCaseHandler::class, //key @handler is defined in composition.
            '@service' => UserService::class, //key @service is reserved
        ],
         */
    ],
    //Defined how will be the composition of the use case handlers to be use it.
    'api-composition' => [
        //handler defines how will be call it when the use case will be defined in the handler section.
        'handler' => '@handler',
        //dependency defines how will be call it when the use case will be defined in the service section.
        'dependency' => '@service',
    ],
    'logs' => [
        'path' => 'logs/use-case-handlers.log',
    ]
];
