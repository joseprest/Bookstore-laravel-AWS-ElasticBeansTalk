<?php



return [

    'disable_source' => env('BANQ_PHOTOS_DISABLE_SOURCE', false),

    'locations' => [

        'Québec' => [
            'position' => [
                'latitude' => 46.813878,
                'longitude' => -71.207981
            ]
        ],

        '/Québec (Province)/' => [
            'name' => 'Québec (Province)',
            'position' => [
                'latitude' => 49.587088,
                'longitude' => -73.637027
            ]
        ],

        'Montréal' => [
            'position' => [
                'latitude' => 45.5,
                'longitude' => -73.3
            ]
        ]
    ]

];
