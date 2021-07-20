<?php


return [

    'query_from_last_update' => env('PRETNUMERIQUE_QUERY_FROM_LAST_UPDATE', true),

    'libraries_from_database' => env('PRETNUMERIQUE_LIBRARIES_FROM_DATABASE', true),

    'libraries' => [
        'banq',
        'brossard',
        'quebec',
        'reseaubiblioestrie',
        'sainte-catherine',
        'riviere-du-loup',
        'saint-lambert',
        'chum',
        'alice-lane',
    ],

    'excluded_categories' => [
        // 'romance'
    ],

    'categories' => [
        'business-economics-law' => 'Affaires, économie et droit',
        'arts' => 'Arts, architecture et design',
        'comics' => 'Bandes dessinées',
        'biography' => 'Biographies',
        'self-help' => 'Croissance personnelle',
        'cooking' => 'Cuisine',
        'decoration-gardening' => 'Décoration et jardinage',
        'education-languages' => 'Éducation et langues',
        'essays-literary-criticism' => 'Essais littéraires et critique',
        'family' => 'Famille et maternité',
        'computers' => 'Informatique',
        'juvenile-fiction' => 'Jeunesse - albums et romans',
        'juvenile-comics' => 'Jeunesse - bandes dessinées',
        'juvenile-nonfiction' => 'Jeunesse - documentaires',
        'nature-environment' => 'Nature et environnement',
        'reference' => 'Ouvrages de référence',
        'parapsychology' => 'Parapsychologie',
        'poetry-drama' => 'Poésie et théâtre',
        'psychology' => 'Psychologie',
        'religion-spirituality' => 'Religion et spiritualité',
        'novel-fiction' => 'Romans et nouvelles',
        'historical-fiction' => 'Romans historiques',
        'detective-suspense' => 'Romans policiers et suspense',
        'science-fiction-fantasy' => 'Romans science-fiction et fantastique',
        'romance' => 'Romans sentimentaux',
        'health' => 'Santé',
        'science-technology' => 'Science et technologie',
        'social-science' => 'Sciences humaines et sociales',
        'sports-hobbies' => 'Sports et loisirs',
        'tourism-travel' => 'Tourisme et voyages',
        'transportation' => 'Transports'
    ]

];
