<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Use Case Handlers Information (Nicolas RO)
    |--------------------------------------------------------------------------
    |   Put here all your use case handlers that will be used in your application.
    |   The use case handlers are the classes that will handle the use cases for each module.
    |   If you want to create a new use case handler, you must create a new class that implements UseCaseHandlerSchema.
    |   Note: The class name must be the same as the module name (e.g. UserUseCaseHandler).
    |   And of course the class must be in the namespace of the module that it is related to.
    |   Finally, the use case handler working with Dependency Inversion principle.
        So, build your handler using a Repository as a dependency.
    |    example:
    |   'USER-USE-CASE-HANDLER' => new UserUseCaseHandler(repository: new UserService())
    */
];
