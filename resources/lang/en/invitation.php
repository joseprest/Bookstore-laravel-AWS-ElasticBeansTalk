<?php

/**
 * Texts for a invitation related
 */
return [
    'processing' => 'Invitation in progress...',
    'deletion' => [
        'title' => 'Delete the invitation',
        'confirmation' => 'Are you sure you want to delete this invitation?',
    ],
    'linking' => [
        'title' => 'Welcome to Manivelle!',
        'intro' => 'You have been invited to an organisation',
    ],
    'email' => [
        'subject' => 'You have been invited to :organisation',
        'body' => '<p>Hello,</p>' .

            '<p>You have been invited as a  ":role" to the organisation ":organisation".</p>' .
            '<p>Follow the link below to accept the invitation :<br/><a href=":link_url">:link_url</a></p>',
    ],
    'actions' => [
        'edit' => 'Modify the invitation',
        'send' => 'Send the invitation',
        'accept' => 'Accept the invitation',
    ],
];
