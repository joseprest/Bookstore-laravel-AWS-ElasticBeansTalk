<?php

/**
 * Texts for the general interface (visible on (almost) all pages)
 */
return [
    'site_title' => 'Manivelle',
    'pending_tasks' => [
        'confirmation' => 'Des tâches sont encore en cours d\'exécution. Elles devraient prendre quelques secondes pour se terminer.',
        'alert' => [
            'title' => 'Tâches en cours d\'exécution : ',
            'message' => ':nbTasks encore en cours d\'exécution. Si vous quittez la page maintenant, des données pourraient être perdues.',
        ],
        'one_task_is' => 'Une tâche est',
        'nb_tasks_are' => ':nb tâches sont',
        'tasks_executing' => ':tasks en cours d\'exécution',
    ],
    'footer' => [
        'partners' => [
            'text' => 'La plateforme Manivelle a été soutenue grâce à la participation financière de',
        ],
    ],
    'nav' => [
        // "Organisation" menu (for the current organisation)
        'organisation' => [
            'see_screens' => 'Voir les écrans',
            'modify' => 'Modifier l\'organisation',
        ],

        // "Organisations" menu (listing all organisations)
        'organisations' => [
            'title' => 'Organisations',
        ],

        // "Screens" menu (shown when viewing a screen)
        "screens" => [
            'title' => 'Écrans',
        ],

        // "User" menu (top right)
        "user" => [
            'my_account' => 'Compte',
            'admin' => 'Administration',
            'logout' => 'Déconnexion',
        ],
    ],
];
