<?php

return [
    'actions' => [
        'add' => 'Ajouter une organisation',
    ],
    'edition' => [
        'title' => 'Modifier l\'organisation',
    ],
    'creation' => [
        'title' => 'Créer une organisation',
    ],
    'inputs' => [
        'name' => 'Nom de l\'organisation',
        'slug' => 'Adresse de l\'organisation',
        'emails' => [
            'settings' => 'Paramètres des courriels',
            'from_name' => 'Nom d\'expéditeur',
            'from' => 'Courriel d\'expéditeur',
            'reply_to' => 'Adresse courriel de réponse',
            'smtp' => 'Serveur d\'envoi de courriel (SMTP)',
            'subject' => 'Sujet des messages',
        ],
        'sms' => [
            'settings' => 'Paramètres des SMS',
            'body' => 'Message du SMS',
        ],
        'channel' => [
            'settings' => 'Paramètres de la chaîne',
            'pretnumerique_id' => 'ID Prêt numérique'
        ]
    ],
];
