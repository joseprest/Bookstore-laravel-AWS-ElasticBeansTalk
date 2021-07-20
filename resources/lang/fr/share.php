<?php

return [
    'email' => [
        'subject' => 'Partagé depuis une Manivelle',
        'body' => '<p>Bonjour,</p>'.
                '<p>Voici le lien que vous avez partagé à partir d\'une manivelle:<br/>'.
                '<a href=":url">:url</a></p>',
        'footer' => 'Ce courriel a été envoyé depuis un écran <a href="http://manivelle.io">Manivelle</a>.',
        'footer_vaudreuil' => 'Ce courriel a été envoyé depuis un écran <a href="https://jesuismozaik.com/">Mozaïk</a>.',
        'layouts' => [
            'card' => [
                'message_from' => 'Message de :from',
            ],
            'photo' => [
                'credit' => 'Crédit photo: :credit',
            ],
        ],
    ],
    'sms' => [
        'body' => 'Partagé depuis une Manivelle :url',
    ],
    'bubbles' => [
        'banq_card' => [
            'see_entry' => 'Voir la fiche complète',
        ],
        'banq_photo' => [
            'see_entry' => 'Voir la fiche complète',
        ],
        'banq_question' => [
            'see_entry' => 'Voir la réponse',
        ],
        'banq_service' => [
            'see_entry' => 'Voir sur le web',
        ],
        'announcement' => [
            'see_entry' => 'Voir sur le web',
        ],
        'location' => [
            'see_entry' => 'Voir le lieu',
        ],
        'book' => [
            'borrow' => 'Emprunter',
            'see_more' => 'Voir d\'autres livres numériques ou obtenir de l\'aide',
            'see_more_url' => 'http://www.banq.qc.ca/ressources_en_ligne/livres-numeriques/index.html',
        ],
        'publication' => [
            'see_entry' => 'Consulter le document',
            'see_more' => 'Voir d\'autres ebooks',
            'see_more_url' => 'https://www-cairn-info.scd-proxy.univ-brest.fr/ouvrages.php',
            'footer' => 'Cet email a été envoyé depuis un écran <a href="http://manivelle.io">Manivelle</a> de l\'UBO.',
        ],
        'event' => [
            'see_entry' => 'Voir la fiche complète',
            'see_more' => 'Voir d\'autres événements',
            'see_more_url' => 'http://murmitoyen.com',
        ],
        'banq_book' => [
            'see_availability' => 'Vérifier la disponibilité',
            'see_more' => 'Découvrir d\'autres romans',
            'see_more_url' => 'http://www.banq.qc.ca/ressources_en_ligne/romansalire/',
        ],
    ],
];
