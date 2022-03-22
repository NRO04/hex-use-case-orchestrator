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
            '@handler' => UserUseCaseHandler::class,
            '@services' => [
                'serviceA' => ServiceA::class,
                'serviceB' => ServiceB::class
            ],
            '@useCases' => [
                'useCaseA' => UseCaseA::class,
                'useCaseB' => UseCaseB::class
            ],
            '@compose' => [
                'serviceA' => 'useCaseA'
                'serviceB' => 'useCaseB'
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
    'logs' => [
        'path' => 'logs/use-case-handlers.log',
    ]
];
