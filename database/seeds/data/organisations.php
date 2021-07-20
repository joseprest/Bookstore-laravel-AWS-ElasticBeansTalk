<?php


$data = [
    [
        'name' => 'UBO',
        'slug' => 'ubo',
        'users' => array(
            array(
                'email' => 'info@univ-brest.fr',
                'role' => 'organisation.admin'
            ),
            array(
                'email' => 'tech@manivelle.io',
                'role' => 'organisation.admin'
            ),
            array(
                'email' => 'info@atelierfolklore.ca',
                'role' => 'organisation.admin'
            )
        )
    ],
    [
        'name' => 'Saint-Camille',
        'slug' => 'saintcamille',
        'users' => array(
            array(
                'email' => 'info@saint-camille.ca',
                'role' => 'organisation.admin'
            ),
            array(
                'email' => 'tech@manivelle.io',
                'role' => 'organisation.admin'
            ),
            array(
                'email' => 'info@atelierfolklore.ca',
                'role' => 'organisation.admin'
            )
        )
    ],
    [
        'name' => 'CHUM',
        'slug' => 'chum',
        'users' => array(
            array(
                'email' => 'tech@manivelle.io',
                'role' => 'organisation.admin'
            ),
            array(
                'email' => 'info@atelierfolklore.ca',
                'role' => 'organisation.admin'
            )
        )
    ],
];

$env = App::environment();
if ($env === 'local' || $env === 'stage') {
    $data = array_merge($data, [
        [
            'name' => 'BANQ',
            'slug' => 'banq',
            'users' => array(
                array(
                    'email' => 'info@banq.qc.ca',
                    'role' => 'organisation.admin'
                ),
                array(
                    'email' => 'tech@manivelle.io',
                    'role' => 'organisation.admin'
                )
            ),
            'settings' => [
                'default_channels' => [
                    'books', 'banq_books', 'banq_cards', 'banq_photos', 'banq_quizz', 'banq_services'
                ],
                'email_from_name' => 'BAnQ - Services aux usagers',
                'email_subject' => 'Partagé depuis un écran interactif de BAnQ',
                'sms_body' => 'Partagé depuis écran interactif BAnQ',
                'pretnumerique_id' => 'banq'
            ]
        ],
        [
            'name' => 'Brossard',
            'slug' => 'brossard',
            'users' => array(
                array(
                    'email' => 'tech@manivelle.io',
                    'role' => 'organisation.admin'
                )
            ),
            'settings' => [
                'default_channels' => [
                    'books', 'events'
                ],
                'pretnumerique_id' => 'brossard'
            ]
        ],
        [
            'name' => 'Québec',
            'slug' => 'quebec',
            'users' => array(
                array(
                    'email' => 'tech@manivelle.io',
                    'role' => 'organisation.admin'
                )
            ),
            'settings' => [
                'default_channels' => [
                    'books', 'events'
                ],
                'pretnumerique_id' => 'quebec'
            ]
        ],
        [
            'name' => 'Temps libre',
            'slug' => 'tempslibre',
            'users' => array(
                array(
                    'email' => 'tech@manivelle.io',
                    'role' => 'organisation.admin'
                )
            ),
            'settings' => [
                'default_channels' => [
                    'books', 'events'
                ],
                'pretnumerique_id' => 'quebec'
            ]
        ],
        [
            'name' => 'Saint-Lambert',
            'slug' => 'saint-lambert',
            'users' => array(
                array(
                    'email' => 'tech@manivelle.io',
                    'role' => 'organisation.admin'
                )
            ),
            'settings' => [
                'default_channels' => [
                    'books', 'events', 'quizz'
                ],
                'pretnumerique_id' => 'saint-lambert'
            ]
        ],
        [
            'name' => 'Vaudreuil',
            'slug' => 'vaudreuil',
            'users' => array(
                array(
                    'email' => 'tech@manivelle.io',
                    'role' => 'organisation.admin'
                )
            ),
            'settings' => [
                'default_channels' => [
                    'quizz_vaudreuil',
                    'announcements_vaudreuil',
                    'locations_vaudreuil',
                    'events_vaudreuil'
                ],
                'pretnumerique_id' => 'saint-lambert'
            ]
        ],
        [
            'name' => 'Baie-Comeau',
            'slug' => 'baie-comeau',
            'users' => array(
                array(
                    'email' => 'tech@manivelle.io',
                    'role' => 'organisation.admin'
                )
            ),
            'settings' => [
                'default_channels' => [
                    'books', 'events'
                ],
                'pretnumerique_id' => 'alice-lane'
            ]
        ],
    ]);
}

return $data;
