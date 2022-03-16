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
            'compose' => [
                'serviceA' => 'useCaseA'
                'serviceB' => 'useCaseB'
            ]
        ],
         */
    ],
    //Defines how will be the composition of the use case handlers to be used it.
    'handler-composition-api' => [
        /* handler property is reserved and this one, defines how the property will be nominated
        in the handler section when the use case been statement.
        You can change the default alias of the handler => @handler, if you want to use a different name.
        It is important to know that the handler property value must be defined in the handler section.
         And this one must be the same as the key of the handler in the handlers section.
        */
        'resolve' => [

            'alias' => [
                'handler' => '@handler',
                'dependencies' => '@services',  //dependency defines how will be call it when the use case will be defined in the service section.
                'use-cases' => '@useCases'
            ]
        ]
    ],
    'logs' => [
        'path' => 'logs/use-case-handlers.log',
    ]
];
