<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => 'manivelle.io',
        'secret' => env('MAILGUN_SECRET')
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET', ''),
    ],

    'ses' => [
        'key'    => '',
        'secret' => '',
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => Manivelle\User::class,
        'key'    => '',
        'secret' => '',
    ],

    'google' => [
        'key' => env('GOOGLE_KEY', ''),
        'secret' => env('GOOGLE_SECRET', ''),
        'analytics' => env('GOOGLE_ANALYTICS', 'UA-79618919-1'),
        'analytics_screen' => env('GOOGLE_ANALYTICS_SCREEN', 'UA-79618919-3')
    ],

    'pubnub' => [
        'publish_key' => env('PUBNUB_PUBLISH_KEY', ''),
        'subscribe_key' => env('PUBNUB_SUBSCRIBE_KEY', ''),
        'namespace' => env('PUBNUB_NAMESPACE', 'manivelle')
    ],

    'twilio' => [
        'from' => [
            '+1' => env('TWILIO_FROM_AMERICA', env('TWILIO_FROM', '+18559806002')),
            '+33' => env('TWILIO_FROM_FRANCE', '+33644649957'),
        ],
        'sid' => env('TWILIO_SID', ''),
        'token' => env('TWILIO_TOKEN', '')
    ],

    'newrelic' => [
        'enabled' => false,
        'app_name' => (env('APP_WORKER', false) ? 'manivelle-worker':'manivelle-backend').' ('.env('APP_HOST', 'manivelle.io').')'
    ],

    'rollbar' => [
        'enabled' => false,
        'access_token' => '83448b5d324f4d2e97f8fbde70a0448f',
        'environment' => (env('APP_WORKER', false) ? 'manivelle-worker':'manivelle-backend').' ('.env('APP_HOST', 'manivelle.io').')'
    ],

    'loggly' => [
        'enabled' => true,
        'token' => env('LOGGLY_TOKEN'),
        'tag' => (env('APP_WORKER', false) ? 'manivelle-worker':'manivelle-backend').'-'.env('APP_HOST', 'manivelle.io')
    ],

    'adobe_fmc' => [
        'app_id' => '1516.25017.143657',
        'site_name' => 'Manivelle',
        'media_name' => 'Web',
    ],

];
