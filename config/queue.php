<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Queue Driver
    |--------------------------------------------------------------------------
    |
    | The Laravel queue API supports a variety of back-ends via an unified
    | API, giving you convenient access to each back-end using the same
    | syntax for each one. Here you may set the default queue driver.
    |
    | Supported: "null", "sync", "database", "beanstalkd",
    |            "sqs", "iron", "redis"
    |
    */

    'default' => 'main',

    /*
    |--------------------------------------------------------------------------
    | Queue Connections
    |--------------------------------------------------------------------------
    |
    | Here you may configure the connection information for each server that
    | is used by your application. A default configuration has been added
    | for each back-end shipped with Laravel. You are free to add more.
    |
    */

    'connections' => [

        'main' => [
            'driver' => env('QUEUE_DRIVER', 'sqs'),
            'key'    => env('AWS_KEY', 'your-public-key'),
            'secret' => env('AWS_SECRET', 'your-public-key'),
            'prefix'  => env('AWS_SQS_PREFIX', ''),
            'queue'  => env('AWS_SQS_QUEUE', 'your-queue'),
            'region' => env('AWS_REGION', 'us-east-1'),
        ],
        
        'priority' => [
            'driver' => env('QUEUE_PRIORITY_DRIVER', 'sqs'),
            'key'    => env('AWS_KEY', 'your-public-key'),
            'secret' => env('AWS_SECRET', 'your-public-key'),
            'prefix'  => env('AWS_SQS_PREFIX', 'sqs-url'),
            'queue'  => env('AWS_SQS_QUEUE_PRIORITY', 'prod_priority'),
            'region' => 'us-east-1'
        ],
        
        'sources' => [
            'driver' => env('QUEUE_SOURCES_DRIVER', 'sources'),
            'queue' => 'default',
            'expire' => 60 * 20,
        ],

        'sync' => [
            'driver' => 'sync',
        ],

        'database' => [
            'driver' => 'database',
            'table' => 'jobs',
            'queue' => 'default',
            'expire' => 60 * 20,
        ],

        'beanstalkd' => [
            'driver' => 'beanstalkd',
            'host'   => 'localhost',
            'queue'  => 'default',
            'ttr'    => 60,
        ],

        'iron' => [
            'driver'  => 'iron',
            'host'    => 'mq-aws-us-east-1.iron.io',
            'token'   => 'your-token',
            'project' => 'your-project-id',
            'queue'   => 'your-queue-name',
            'encrypt' => true,
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue'  => 'default',
            'expire' => 60,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Failed Queue Jobs
    |--------------------------------------------------------------------------
    |
    | These options configure the behavior of failed queue job logging so you
    | can control which database and table are used to store the jobs that
    | have failed. You may change them to any database / table you wish.
    |
    */

    'failed' => [
        'database' => 'mysql', 'table' => 'failed_jobs',
    ],

];
