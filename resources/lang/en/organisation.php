<?php

return [
    'actions' => [
        'add' => 'Add an organization',
    ],
    'edition' => [
        'title' => 'Modify the organization',
    ],
    'creation' => [
        'title' => 'Create an organization',
    ],
    'inputs' => [
        'name' => 'Name of the organization',
        'slug' => 'Address of the organization',
        'emails' => [
            'settings' => 'E-mail settings',
            'from_name' => 'Name of sender',
            'from' => 'E-mail of sender',
            'reply_to' => 'Response Email Address',
            'smtp' => 'Email Server (SMTP)',
            'subject' => 'Message topics',
        ],
        'sms' => [
            'settings' => 'SMS settings',
            'body' => 'SMS message',
        ],
        'channel' => [
            'settings' => 'Channel settings',
            'pretnumerique_id' => 'Prêt numérique ID'
        ]
    ],
];
