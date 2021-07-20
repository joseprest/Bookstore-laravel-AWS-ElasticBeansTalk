<?php

return [

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application is a worker
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'worker' => env('APP_WORKER', false),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', env('CDN_HOST', 'http://clients.manivelle.io')),

    /*
    |--------------------------------------------------------------------------
    | Application domains
    |--------------------------------------------------------------------------
    |
    */

    'domains' => [
        'default' => env('APP_DOMAIN_DEFAULT', env('APP_DOMAIN', 'clients.manivelle.io')),
        'api' => env('APP_DOMAIN_API', 'api.'.env('APP_DOMAIN', 'manivelle.io')),
        'organisation' => env('APP_DOMAIN_ORGANISATION', '{organisation}.'.env('APP_DOMAIN', 'manivelle.io')),
        'screen' => env('APP_DOMAIN_SCREEN', '{screen_uuid}.ecrans.'.env('APP_DOMAIN', 'manivelle.io')),
    ],


    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'America/Montreal',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'fr',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'fr',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY', 'SomeRandomString'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log settings for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Settings: "single", "daily", "syslog", "errorlog"
    |
    */

    'log' => env('APP_LOG', 'single'),

    'log_default' => env('APP_LOG_DEFAULT', storage_path('logs/laravel.log')),
    'log_info' => env('APP_LOG_INFO', storage_path('logs/app.log')),
    'log_scheduler' => env('APP_LOG_SCHEDULER', storage_path('logs/scheduler.log')),

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /**
         * Vendors
         */
        Barryvdh\Debugbar\ServiceProvider::class,
        Barryvdh\Cors\ServiceProvider::class,
        Folklore\LaravelLocale\LocaleServiceProvider::class,
        Folklore\Image\ImageServiceProvider::class,

        /**
         * Panneau vendors
         */
        Orchestra\Html\HtmlServiceProvider::class,
        Orchestra\Asset\AssetServiceProvider::class,
        Conner\Tagging\Providers\TaggingServiceProvider::class,
        Folklore\GraphQL\GraphQLServiceProvider::class,
        Folklore\EloquentMediatheque\MediathequeServiceProvider::class,
        Cviebrock\EloquentSluggable\SluggableServiceProvider::class,
        Bican\Roles\RolesServiceProvider::class,

        /**
         * Panneau
         */
        Panneau\PanneauServiceProvider::class,
        Panneau\Auth\AuthServiceProvider::class,
        Panneau\Mediatheque\MediathequeServiceProvider::class,
        Panneau\Bubbles\BubblesServiceProvider::class,

        /*
         * Application Service Providers...
         */
        Manivelle\Providers\AppServiceProvider::class,
        Manivelle\Providers\AuthServiceProvider::class,
        Manivelle\Providers\EventServiceProvider::class,
        Manivelle\Providers\RouteServiceProvider::class,
        Manivelle\Providers\QueueServiceProvider::class,
        Manivelle\Providers\ImageServiceProvider::class,
        Manivelle\Providers\ViewsServiceProvider::class,
        Manivelle\Providers\ValidationServiceProvider::class,

        Manivelle\Providers\ManivelleServiceProvider::class,
        Manivelle\Providers\LocalizerServiceProvider::class,

        Manivelle\Panneau\PanneauServiceProvider::class,
        Manivelle\Panneau\UsersServiceProvider::class,

        Manivelle\Channels\Books\BooksServiceProvider::class,
        Manivelle\Channels\Events\EventsServiceProvider::class,
        Manivelle\Channels\Publications\PublicationsServiceProvider::class,
        Manivelle\Channels\Quizz\QuizzServiceProvider::class,
        Manivelle\Channels\Services\ServicesServiceProvider::class,
        Manivelle\Channels\Announcements\AnnouncementsServiceProvider::class,
        Manivelle\Channels\Locations\LocationsServiceProvider::class,
        Manivelle\Banq\BanqServiceProvider::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,
        'Str' => Illuminate\Support\Str::class,

        'Asset' => Orchestra\Support\Facades\Asset::class,
        'Form' => Orchestra\Support\Facades\Form::class,
        'HTML' => Orchestra\Support\Facades\HTML::class,
        'Table' => Orchestra\Support\Facades\Table::class,

        'Image' => Folklore\Image\Facades\Image::class,

        'Debugbar' => Barryvdh\Debugbar\Facade::class,

        'Panneau' => Panneau\Support\Facades\Panneau::class,
        'Manivelle' => Manivelle\Support\Facades\Manivelle::class,
        'Localizer' => Manivelle\Support\Facades\Localizer::class,
        'GraphQL' => Folklore\GraphQL\Support\Facades\GraphQL::class

    ],

];
