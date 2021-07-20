<?php

/**
 * Generic texts for a screen
 */
return [
    'screens' => 'Écrans',
    'view_live' => 'Voir l\'écran',
    'currently_offline' => 'Cet écran est actuellement hors ligne',
    'descriptions' => [
        'with_size' => ':type de :size',
    ],
    'orientations' => [
        'horizontal' => 'Écran horizontal',
        'vertical' => 'Écran vertical',
        'square' => 'Écran carré',
    ],
    'actions' => [
        'add' => 'Ajouter un écran',
        'create' => 'Créer un écran',
    ],
    'deletion' => [
        'title' => 'Supprimer l\'écran',
        'description' => 'En supprimant l\'écran, vous enlever le lien entre celui-ci et votre organisation. '.
            'Vous pourrez l\'ajouter à nouveau en utilisant le code d\'authentification visible sur l\'écran.',
        'confirmation' => 'Êtes-vous certain de vouloir supprimer cet écran?',
    ],
    'tabs' => [
        'slideshow' => 'Diaporama',
        'notifications' => 'Notifications',
        'channels' => 'Chaînes',
        'settings' => 'Paramètres',
        'controls' => 'Contrôles',
        'stats' => 'Statistiques',
    ],
    'inputs' => [
        'name' => 'Nom de l\'écran',
        'location' => 'Localisation',
        'technical_info' => 'Informations techniques',
        'slug' => 'Adresse de l\'interface (facultatif)',
        'permalink' => 'Adresse permanente: :url',
        'display_settings' => 'Paramètres d\'affichage',
        'auth_code' => 'Code d\'authentification',
        'hide_header' => 'Cacher l\'en-tête avec l\'heure',
        'hide_summaries' => 'Cacher les résumés dans le menu',
        'country_code' => 'Indicatif du pays (Pour l\'envoi de SMS)',
        'disable_manivelle' => 'Désactiver la manivelle',
        'channels_menu_always_visible' => 'Menu des chaînes toujours visible',
        'header_title' => 'Titre dans l\'entête',
        'disable_slideshow' => 'Désactiver le diaporama (Toujours sur le menu d\'accueil)',
        'theme' => 'Thème',
        'start_view' => [
            'label' => 'Écran de démarrage',
            'default' => 'Diaporama (Par défaut)',
        ],
        'default_locale' => 'Langue de l\'interface',
        'keyboard_alternative_layout' => 'Clavier'
    ],
    'statuses' => [
        'online' => 'Connecté',
        'offline' => 'Déconnecté',
    ],
    'commands' => [
        'actions' => [
            'restart' => 'Redémarrer',
            'update' => 'Mise à jour',
        ],
        'computer' => 'Ordinateur',
        'server' => 'Serveur',
        'application' => 'Application',
        'server_application' => 'Serveur et Application',
        'ssh_tunnel' => 'Tunnels SSH',
        'code' => 'Code',
        'npm' => 'NPM',
    ],
    'log' => [
        'last_ping' => 'Dernier ping: :time',
        'server_ping' => 'Ping du serveur',
        'sent_at' => 'Envoyé: :time',
        'executed_at' => 'Exécuté: :time',
        'command' => 'Commande: :command',
        'details' => 'Détails',
    ],
    'ping_data' => [
        'online_since' => 'En ligne depuis: :time',
        'memory_free' => 'Mémoire libre: :amount',
        'memory_total' => 'Mémoire totale: :amount',
        'load' => 'Charge: :load',
    ],
    'create' => [
        'title' => 'Créer un écran',
    ],
    'linking' => [
        'title' => 'Lier un écran',
        'ensure_connected' => 'Assurez-vous que l\'écran soit allumé et connecté à internet.',
        'message_code' => 'Ce code de 4 chiffres apparaît à l\'écran lors de sa première mise en marche.',
    ],
    'settings' => 'Paramètres de l\'écran',
    'controls' => 'Contrôle de l\'écran',
];
