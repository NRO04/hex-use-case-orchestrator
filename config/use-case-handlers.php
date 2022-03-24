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
            '@handler' => UserUseCaseHandler::class,    // The handler class.
            '@services' => [                            // Records of services to inject in the uses cases.
                'service-a-ref' => ServiceA::class,     // service-a-ref is the key of the service to inject.
                'service-b-ref' => ServiceB::class      // service-b-ref is the key of the service to inject.
            ],
            '@useCases' => [                            // Records of use cases to compose.
                'use-case-a-ref' => UseCaseA::class,    // use-case-a-ref is the key of the use case to compose.
                'use-case-b-ref' => UseCaseB::class,    // use-case-b-ref is the key of the use case to compose.
            ],
            '@compose' => [                             // Records of use cases to inject in the handler.
                'use-case-name' => [ 'service-a-ref' => 'use-case-a-ref'],  // use-case-name is the key of the use case to inject in the handler.
                'use-case-name-2' => [ 'service-b-ref' => 'use-case-b-ref'], // use-case-name-2 is the key of the use case to inject in the handler.
            ]
        ],
         */
    ],
    //Defines how will be the composition of the use case handlers to be used it.
    'handler-composition-api' => [


        /*
        |--------------------------------------------------------------------------
        | (Resolve)
        |--------------------------------------------------------------------------
        | You can change the default alias if you want to use a different name.
        | It is important to know that the alias that was defined in the metadata, must also be defined in the handler section,
        | And its value must be declared as a key in the handler section.
        */
        'resolve' => [

            'alias' => [
                'handler' => '@handler',
                'dependencies' => '@services',
                'use-cases' => '@useCases',
                'compose' => '@compose'
            ]
        ]
    ],
    'allow-empty-handlers' => false,
    'logs' => [
        'path' => 'logs/use-case-handlers.log',
    ]
];
