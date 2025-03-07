<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Authentication Guard
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication guard that will be used
    | by your application. You can modify this value to any of the available
    | guards, but the "web" guard will be used if this is left unchanged.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Here you may define every authentication guard for your application. Of
    | course, a great default configuration has been defined for you here
    | using session storage and the Eloquent user provider. You may change
    | these settings as required.
    |
    | Supported: "session", "token"
    |
    */

    // config/auth.php
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'sanctum',
            'provider' => 'users',
            'hash' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are retrieved from your database or other storage mechanisms
    | used by your application to persist your user's data.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify the password reset options for your application. These
    | options control how long the reset token should be valid for, as well
    | as the name of the table that holds your password reset tokens.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation
    |--------------------------------------------------------------------------
    |
    | Here you may specify the duration in seconds before the password
    | confirmation will expire. If the user does not confirm their password
    | within this time, they will be logged out and required to re-enter
    | their password. A value of 0 means that password confirmation is
    | disabled.
    |
    */

    'password_timeout' => 10800,

];
