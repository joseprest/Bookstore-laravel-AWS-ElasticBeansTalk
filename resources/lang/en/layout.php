<?php

/**
 * Texts for the general interface (visible on (almost) all pages)
 */
return [
    'site_title' => 'Manivelle',
    'pending_tasks' => [
        'confirmation' => 'Tasks are still running. They should take a few seconds to finish.',
        'alert' => [
            'title' => 'Tasks are still running: ',
            'message' => ':nbTasks still running. If you leave the page now, data could be lost.',
        ],
        'one_task_is' => 'A task is',
        'nb_tasks_are' => ':nb tasks are',
        'tasks_executing' => ':tasks still running',
    ],
    'footer' => [
        'partners' => [
            'text' => 'The Manivelle platform was supported thanks to the financial contribution of', // À vérifier
        ],
    ],
    'nav' => [
        // "Organisation" menu (for the current organisation)
        'organisation' => [
            'see_screens' => 'See screens',
            'modify' => 'Modify the organization',
        ],

        // "Organisations" menu (listing all organisations)
        'organisations' => [
            'title' => 'Organizations',
        ],

        // "Screens" menu (shown when viewing a screen)
        "screens" => [
            'title' => 'Screens',
        ],

        // "User" menu (top right)
        "user" => [
            'my_account' => 'Account',
            'admin' => 'Administration',
            'logout' => 'Logout',
        ],
    ],
];
