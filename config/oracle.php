<?php

return [
    'oracle' => [
        'driver' => 'oracle',
        'host' => env('DB_HOSTORACLE', ''),
        'port' => env('DB_PORTORACLE', '1521'),
        'database' => env('DB_DATABASEORACLE', ''),
        'service_name' => env('DB_SERVICENAME', ''),
        'username' => env('DB_USERNAMEORACLE', ''),
        'password' => env('DB_PASSWORDORACLE', ''),
        'charset' => '',
        'prefix' => '',
    ],
    'mcframe'=> [
        'driver' => 'oracle',
        'host' => env('DB_HOSTMCF', ''),
        'port' => env('DB_PORTMCF', '1521'),
        'database' => env('DB_DATABASEMCF', ''),
        'service_name' => env('DB_SERVICENAMEMCF', ''),
        'username' => env('DB_USERNAMEMCF', ''),
        'password' => env('DB_PASSWORDMCF', ''),
        'charset' => '',
        'prefix' => '',
    ],
];
