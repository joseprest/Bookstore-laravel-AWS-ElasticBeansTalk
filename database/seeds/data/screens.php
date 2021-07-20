<?php

$data = [
    // [
    //     'auth_code' => '1324',
    //     'name' => 'Écran',
    //     'fields' => [
    //         'technical' => [
    //             'resolution' => [
    //                 'x' => 1080,
    //                 'y' => 1920
    //             ]
    //         ]
    //     ],
    //     'organisations' => [
    //         'ubo'
    //     ],
    //     'channels' => [
    //         'books',
    //         'events'
    //     ]
    // ],

    //Temps libre
    [
        'id' => '58',
        'channels' => [
            'books',
            'events',
            'banq_books',
            'banq_photos',
            'banq_cards',
            'banq_quizz',
            'banq_services'
        ]
    ]
];

$env = App::environment();
if ($env === 'local' || $env === 'stage') {
    $data = array_merge($data, [
        [
            'auth_code' => '7',
            'name' => 'Entrée',
            'organisations' => [
                'tempslibre'
            ],
            'channels' => [
                'books',
                'events'
            ],
            'fields' => [
                'technical' => [
                    'resolution' => [
                        'x' => 1080,
                        'y' => 1920
                    ]
                ]
            ]
        ],
        [
            'auth_code' => '2',
            'name' => 'Hall d\'entrée',
            'organisations' => [
                'brossard'
            ],
            'channels' => [
                'books',
                'events'
            ],
            'fields' => [
                'technical' => [
                    'resolution' => [
                        'x' => 1080,
                        'y' => 1920
                    ]
                ]
            ]
        ],
        [
            'auth_code' => '3',
            'name' => 'Hall d\'entrée',
            'organisations' => [
                'banq'
            ],
            'channels' => [
                'books',
                'banq_books',
                'banq_photos',
                'banq_cards',
                'banq_quizz',
                'banq_services'
            ],
            'fields' => [
                'technical' => [
                    'resolution' => [
                        'x' => 3840,
                        'y' => 2160
                    ]
                ]
            ]
        ]
    ]);
}

return $data;
