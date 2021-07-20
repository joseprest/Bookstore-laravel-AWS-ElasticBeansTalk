<?php

return [
    'actions' => [
        'add' => 'Add a user',
    ],
    'roles' => [
        'admin' => 'Administrator',
        'organisation_admin' => 'Manager',
        'organisation_editor' => 'Editor',
    ],
    'inputs' => [
        'name' => 'Your name',
        'email' => 'Your e-mail',
        'locale' => 'Language of the interface',
        'security' => 'Security',
        'password' => 'Password',
        'confirmPassword' => 'Confirm password',
        'organisations' => 'Your organizations',
    ],
    'deletion' => [
        'title' => 'Delete this User',
        'description' => 'By removing this user, you will no longer have access to Manivelle. This action is irreversible.', //À vérifier
        'confirmation' => 'Are you sure you want to delete this user?',
    ],
];
