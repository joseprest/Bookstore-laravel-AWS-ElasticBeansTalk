<?php

$memcachedServersCount = env('CACHE_MEMCACHED_HOST_COUNT', 1);
$memcachedServers = [];
for ($i = 0; $i < $memcachedServersCount; $i++) {
    $memcachedServers[] = [
        'host' => env('CACHE_MEMCACHED_HOST_'.($i+1), '127.0.0.1'),
        'port' => env('CACHE_MEMCACHED_PORT_'.($i+1), 11211),
        'weight' => env('CACHE_MEMCACHED_WEIGHT_'.($i+1), 100)
    ];
}

return [

    /*
    |--------------------------------------------------------------------------
    | Default Cache Store
    |--------------------------------------------------------------------------
    |
    | This option controls the default cache connection that gets used while
    | using this caching library. This connection is used when another is
    | not explicitly specified when executing a given caching function.
    |
    */

    'default' => env('CACHE_DRIVER', 'database'),

    'namespace' => env('CACHE_NAMESPACE', 'manivelle'),

    /*
    |--------------------------------------------------------------------------
    | Cache Stores
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the cache "stores" for your application as
    | well as their drivers. You may even define multiple stores for the
    | same cache driver to group types of items stored in your caches.
    |
    */

    'stores' => [

        'apc' => [
            'driver' => 'apc',
        ],
        
        'cloud' => [
            'driver' => 'cloud',
        ],

        'array' => [
            'driver' => 'array',
        ],

        'database' => [
            'driver' => 'database',
            'table'  => 'cache',
            'connection' => null,
        ],

        'file' => [
            'driver' => 'file',
            'path'   => storage_path('framework/cache'),
        ],

        'memcached' => [
            'driver'  => 'memcached',
            'servers' => $memcachedServers,
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Key Prefix
    |--------------------------------------------------------------------------
    |
    | When utilizing a RAM based store such as APC or Memcached, there might
    | be other applications utilizing the same cache. So, we'll specify a
    | value to get prefixed to all our keys so we can avoid collisions.
    |
    */

    'prefix' => env('CACHE_PREFIX', 'laravel')

];
