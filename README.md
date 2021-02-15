## LmcUser Authentication for Laminas Api Tools

This is a Laminas Api Tools authentication adapter that uses the LmcUser authentication service. This adapter is used with the [api-tools-mvc-auth](https://api-tools.getlaminas.org/documentation/modules/api-tools-mvc-auth) module.
It allows to use the LmcUser authentication service for an API service.  This useful when adding a Laminas API Tools service to an application that uses LmcUser for user authentication.

A use case would be when an application's front-end code uses API calls to the same domain address without having to supply user credentials with the API request.
If the user is authenticated to the website, the adapter will return a `AuthenticatedIdentity` with the User Entity provided by the LmcUser Authentication Service, otherwise a `GuestIdentity` is returned.


## Requirements

See the content of `composer.json` for dependencies.

## Installation

Using Composer

```shell
$ composer require visto9259/lmcuser-api-tools-auth
```

## Configuration

Please refer to the [Api Tools Laminas MVC Auth](https://api-tools.getlaminas.org/documentation/modules/api-tools-mvc-auth) module reference for details on how to add a custom adapter since the Api Tools Admin user interface does not support custom adapaters.

In your config files, either `local.php`, `global.php` or another autoload config file, add the entry to `api-tools-mvc-auth`:

```php
'api-tools-mvc-auth' => [
   'authentication' => [
      'adapters' => [
         'myLmcUserAuth' => [
            'adapter' => '\LmcUserApiToolsAuth\Adapter\AuthAdapter',
            'options' => [
                'authentication_service' => 'lmcuser_auth_service',
            ],
        ],
      ],
   ], 
]
```

Once the authentication adapter is added to the config file, you can use it to define the authentication type to use in the your service via the Api Tools Admin user interface.
It will appear as (using the above example) `myLmcUserAuth (oauth2)`.

## Issues
Please submit questions and issues [here](https://github.com/visto9259/lmcuser-api-tools-auth/issues).
