<?php


return array(

    'route_prefix' => '',

    'resources' => array(
        //'custom' => Manivelle\Resources\CustomResource::class
    ),

    'fields' => array(
        'Text' => \Panneau\Fields\Text::class,
        'TextLocale' => \Panneau\Fields\TextLocale::class,
        'Select' => \Panneau\Fields\Select::class,
        'Checkbox' => \Panneau\Fields\Checkbox::class,
        'Checkboxes' => \Panneau\Fields\Checkboxes::class,
        'Date' => \Panneau\Fields\Date::class,
        'Time' => \Panneau\Fields\Time::class,
        'Datetime' => \Panneau\Fields\Datetime::class,
        'Dates' => \Panneau\Fields\Dates::class,
        'DateRange' => \Panneau\Fields\DateRange::class,
        'DateRanges' => \Panneau\Fields\DateRanges::class,
        'Link' => \Panneau\Fields\Link::class,
        'Links' => \Panneau\Fields\Links::class,
        'Snippet' => \Panneau\Fields\Snippet::class
    ),

    'views' => array(
        'layout' => 'layouts.main',
        'form' => 'panneau::form',
        'list' => 'panneau::list',
        'auth' => [
            'login' => 'panneau::auth.login',
            'register' => 'panneau::auth.register'
        ]
    ),

    'controllers' => array(
        'auth' => \Manivelle\Http\Controllers\Auth\AuthController::class,
        'upload' => \Panneau\Http\Controllers\UploadController::class
    ),

    'assets' => array(
        'bootstrap' => 'vendor/panneau/css/bootstrap.css',
        'css' => 'vendor/panneau/css/main.css',
        'jquery' => '//code.jquery.com/jquery-2.2.1.min.js',
        'gsap' => '//cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js',
        'ckeditor' => '//cdn.ckeditor.com/4.5.3/standard/ckeditor.js',
        'pubnub' => '//cdn.pubnub.com/sdk/javascript/pubnub.4.4.4.min.js',
        'vendors_js' => array(
            'src' => 'vendor/panneau/js/vendors.js',
            'dependencies' => ['jquery', 'ckeditor', 'gsap', 'pubnub']
        ),
        'panneau' => array(
            'src' => 'vendor/panneau/js/panneau.js',
            'dependencies' => ['vendors_js']
        )
    )

);
