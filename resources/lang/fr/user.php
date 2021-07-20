<?php

return [
    'actions' => [
        'add' => 'Ajouter un utilisateur',
    ],
    'roles' => [
        'admin' => 'Administrateur',
        'organisation_admin' => 'Gestionnaire',
        'organisation_editor' => 'Éditeur',
    ],
    'inputs' => [
        'name' => 'Votre nom',
        'email' => 'Votre courriel',
        'locale' => 'Langue de l\'interface',
        'security' => 'Sécurité',
        'password' => 'Votre mot de passe',
        'confirmPassword' => 'Confirmer le mot de passe',
        'organisations' => 'Vos organisations',
    ],
    'deletion' => [
        'title' => 'Supprimer cet utilisateur',
        'description' => 'En supprimant cet utilisateur, vous n\'aurez plus accès à Manivelle.' . // À vérifier
            ' Cette action est irréversible.',
        'confirmation' => 'Êtes-vous certain de vouloir supprimer cet utilisateur?',
    ],
];
