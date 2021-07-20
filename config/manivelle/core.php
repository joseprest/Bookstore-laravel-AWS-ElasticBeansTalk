<?php

return [
    'cache_namespace' => env('CACHE_NAMESPACE', 'manivelle'),

    'sources' => [

        'murmitoyen' => \Manivelle\Sources\MurMitoyen\MurMitoyen::class,
        'pretnumerique' => \Manivelle\Sources\PretNumerique\PretNumerique::class,
        'cairn' => \Manivelle\Sources\Cairn\Cairn::class,
        'quizz_csv' => \Manivelle\Sources\QuizzCSV\QuizzCSV::class,
        'mosaik_announcements' => \Manivelle\Sources\Mosaik\Announcements::class,
        'mosaik_events' => \Manivelle\Sources\Mosaik\Events::class,
        'mosaik_locations' => \Manivelle\Sources\Mosaik\Locations::class

    ],

    'sourcesWithLibraryList' => [
        'pretnumerique'
    ],
];
