<?php

/**
 * URLs
 */
return [
    'auth' => '/auth',
    'auth.reset_form' => '/password/reset/{token?}',
    'auth.email' => '/password/email',
    'auth.reset' => '/password/reset',
    'account' => '/account',
    'admin' => '/admin',
    'users' => '/users',
    'organisations' => '/organisations',
    'importations' => '/importations',
    'importations.source' => '/importations/{source}',
    'importations.source.editLibraries' => '/importations/{source}/libraries',
    'edit' => '/edit',
    'screens' => '/screens',
    'screens.link' => '/screens/link',
    'screens.slideshow' => '/screens/{screens}/diaporama',
    'screens.notifications' => '/screens/{screens}/notifications',
    'screens.channels' => '/screens/{screens}/channels',
    'screens.channel' => '/screens/{screens}/channels/{channel}',
    'screens.channels.bubbles' => '/screens/{screens}/channels/{channel}/bubbles',
    'screens.channel.settings' => '/screens/{screens}/channels/{channel}/settings',
    'screens.settings' => '/screens/{screens}/settings',
    'screens.controls' => '/screens/{screens}/controle',
    'screens.stats' => '/screens/{screens}/statistics',
    'screens.unlink' => '/screens/{screens}/delete',
    'team' => '/team',
];
