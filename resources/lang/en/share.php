<?php

return [
    'email' => [
        'subject' => 'Shared from a Manivelle',
        'body' => '<p>Hello,</p>'.
                '<p>Here is the link you shared from a Manivelle:<br/>'.
                '<a href=":url">:url</a></p>',
        'footer' => 'This email was sent from a <a href="http://manivelle.io">Manivelle</a> screen.',
        'layouts' => [
            'card' => [
                'message_from' => 'Message from :from',
            ],
            'photo' => [
                'credit' => 'Photo credit: :credit',
            ],
        ],
    ],
    'sms' => [
        'body' => 'Shared from a Manivelle :url',
    ],
    'bubbles' => [
        'banq_card' => [
            'see_entry' => 'View the complete card',
        ],
        'banq_photo' => [
            'see_entry' => 'View the complete card',
        ],
        'banq_question' => [
            'see_entry' => 'View Answer',
        ],
        'banq_service' => [
            'see_entry' => 'View on the web',
        ],
        'book' => [
            'borrow' => 'Borrow',
            'see_more' => 'See other digital books or get help',
            'see_more_url' => 'http://www.banq.qc.ca/ressources_en_ligne/livres-numeriques/index.html',
        ],
        'publication' => [
            'see_entry' => 'View the complete card',
            'see_more' => 'Discover other publications',
            'see_more_url' => 'https://www-cairn-info.scd-proxy.univ-brest.fr/ouvrages.php',
            'footer' => 'This email was sent from a <a href="http://manivelle.io">Manivelle</a> screen at UBO.',
        ],
        'event' => [
            'see_entry' => 'View the complete card',
            'see_more' => 'View other events',
            'see_more_url' => 'http://murmitoyen.com',
        ],
        'banq_book' => [
            'see_availability' => 'Check availability',
            'see_more' => 'Discover other novels',
            'see_more_url' => 'http://www.banq.qc.ca/ressources_en_ligne/romansalire/',
        ],
    ],
];
