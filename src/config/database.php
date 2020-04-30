<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => cloverEnv('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [


        'mysql' => [
            'driver' => 'mysql',
            'host' => cloverEnv('DB_HOST', '127.0.0.1'),
            'port' => cloverEnv('DB_PORT', 3306),
            'database' => cloverEnv('DB_DATABASE', 'forge'),
            'username' => cloverEnv('DB_USERNAME', 'forge'),
            'password' => cloverEnv('DB_PASSWORD', ''),
            'unix_socket' => cloverEnv('DB_SOCKET', ''),
            'charset' => cloverEnv('DB_CHARSET', 'utf8mb4'),
            'collation' => cloverEnv('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => cloverEnv('DB_PREFIX', 'clover'),
            'strict' => cloverEnv('DB_STRICT_MODE', true),
            'engine' => cloverEnv('DB_ENGINE', null),
            'timezone' => cloverEnv('DB_TIMEZONE', '+8:00'),
        ],

    ],


    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => cloverEnv('REDIS_CLIENT', 'phpredis'),

        'cluster' => cloverEnv('REDIS_CLUSTER', false),

        'default' => [
            'host' => cloverEnv('REDIS_HOST', '127.0.0.1'),
            'password' => cloverEnv('REDIS_PASSWORD', null),
            'port' => cloverEnv('REDIS_PORT', 6379),
            'database' => cloverEnv('REDIS_DB', 0),
        ],

        'cache' => [
            'host' => cloverEnv('REDIS_HOST', '127.0.0.1'),
            'password' => cloverEnv('REDIS_PASSWORD', null),
            'port' => cloverEnv('REDIS_PORT', 6379),
            'database' => cloverEnv('REDIS_CACHE_DB', 1),
        ],

    ],

];
