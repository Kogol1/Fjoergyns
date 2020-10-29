<?php

return [
    'default' => 'mysql',
    'migrations' => 'migrations',
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST'),
            'database' => env('DB_DATABASE'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ],
        'mysql_localhost' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST_LOCALHOST'),
            'database' => env('DB_DATABASE_LOCALHOST'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD_DB_LOCALHOST'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ],
        'mysql_vote' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST'),
            'database' => env('DB_DATABASE_VOTE'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ],
        'mysql_token' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST_2'),
            'database' => env('DB_DATABASE_TOKEN'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD_2'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ],
        'mysql_vote_eco' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST'),
            'database' => env('DB_DATABASE_VOTE_ECO'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ],
        'mysql_litebans' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST'),
            'database' => env('DB_DATABASE_LITEBANS'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ],
        'mysql_coreprotect' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST'),
            'database' => env('DB_DATABASE_COREPROTECT'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ],
        'mysql_coreprotect_eco' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST'),
            'database' => env('DB_DATABASE_COREPROTECT_ECO'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ],
        'mysql_plan' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST'),
            'database' => env('DB_DATABASE_PLAN'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ],
    ]
];
