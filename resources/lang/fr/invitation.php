<?php

/**
 * Texts for a invitation related
 */
return [
    'processing' => 'Invitation en cours...',
    'deletion' => [
        'title' => 'Supprimer l\'invitation',
        'confirmation' => 'Êtes-vous certain de vouloir supprimer cette invitation?',
    ],
    'linking' => [
        'title' => 'Bienvenue dans Manivelle !',
        'intro' => 'Vous avez été invité dans une organisation',
    ],
    'email' => [
        'subject' => 'Votre invitation à :organisation',
        'body' => '<p>Bonjour,</p>' .
            '<p>Vous avez été invité en tant que « :role » à l\'organisation « :organisation ».</p>' .
            '<p>Suivez le lien ci-dessous pour accepter l\'invitation :<br/><a href=":link_url">:link_url</a></p>',
    ],
    'actions' => [
        'edit' => 'Modifier une invitation',
        'send' => 'Envoyer l\'invitation',
        'accept' => 'Accepter l\'invitation',
    ],
];
