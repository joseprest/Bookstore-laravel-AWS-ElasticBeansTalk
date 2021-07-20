<?php

/**
 * URLs
 */
return [
    'auth' => '/auth',
    'auth.reset_form' => '/mot-de-passe/reinitialiser/{token?}',
    'auth.email' => '/mot-de-passe/email',
    'auth.reset' => '/mot-de-passe/reinitialiser',
    'account' => '/compte',
    'admin' => '/admin',
    'users' => '/utilisateurs',
    'organisations' => '/organisations',
    'importations' => '/importations',
    'importations.source' => '/importations/{source}',
    'importations.source.editLibraries' => '/importations/{source}/bibliotheques',
    'edit' => '/modifier',
    'screens' => '/ecrans',
    'screens.link' => '/ecrans/lier',
    'screens.slideshow' => '/ecrans/{screens}/diaporama',
    'screens.notifications' => '/ecrans/{screens}/notifications',
    'screens.channels' => '/ecrans/{screens}/chaines',
    'screens.channel' => '/ecrans/{screens}/chaines/{channel}',
    'screens.channels.bubbles' => '/ecrans/{screens}/chaines/{channel}/bubbles',
    'screens.channel.settings' => '/ecrans/{screens}/chaines/{channel}/parametres',
    'screens.settings' => '/ecrans/{screens}/parametres',
    'screens.controls' => '/ecrans/{screens}/controle',
    'screens.stats' => '/ecrans/{screens}/statistiques',
    'screens.unlink' => '/ecrans/{screens}/supprimer',
    'team' => '/equipe',
];
