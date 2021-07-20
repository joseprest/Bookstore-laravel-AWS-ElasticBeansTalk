<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    realpath(__DIR__.'/../')
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    Manivelle\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    Manivelle\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    Manivelle\Exceptions\Handler::class
);


/*
|--------------------------------------------------------------------------
| Log handling
|--------------------------------------------------------------------------
|
*/
$app->configureMonologUsing(function ($monolog) {
    $log = config('app.log');
    $env = config('app.env');
    
    //Info log
    $infoPath = config('app.log_info');
    $infoPath = preg_replace('/(.*)\.log$/', '$1-'.php_sapi_name().'.log', $infoPath);
    if ($log === 'daily') {
        $handler = new Monolog\Handler\RotatingFileHandler($infoPath, 0, Monolog\Logger::INFO, false);
    } else {
        $handler = new Monolog\Handler\StreamHandler($infoPath, Monolog\Logger::INFO, false);
    }
    $output = '[%datetime%] %message% %extra%'.PHP_EOL;
    $formatter = new Monolog\Formatter\LineFormatter($output, 'Y-m-d H:i:s');
    $handler->setFormatter($formatter);
    $monolog->pushHandler($handler);
    
    //Error log
    $laravelPath = config('app.log_default');
    $laravelPath = preg_replace('/(.*)\.log$/', '$1-'.php_sapi_name().'.log', $laravelPath);
    if ($log === 'daily') {
        $handler = new Monolog\Handler\RotatingFileHandler($laravelPath, 0, Monolog\Logger::NOTICE, false);
    } else {
        $handler = new Monolog\Handler\StreamHandler($laravelPath, Monolog\Logger::NOTICE, false);
    }
    $formatter = new Monolog\Formatter\LineFormatter(null, null, true, true);
    $handler->setFormatter($formatter);
    $monolog->pushHandler($handler);
    
    // Don't log to external service when in local
    if ($env === 'local') {
        return null;
    }
    
    //Rollbar
    if (config('services.rollbar.enabled', false)) {
        Rollbar::init([
            'access_token' => config('services.rollbar.access_token'),
            'environment' => config('services.rollbar.environment'),
            'root' => base_path()
        ]);
        $handler = new Monolog\Handler\RollbarHandler(Rollbar::$instance, Monolog\Logger::ERROR, true);
        $monolog->pushHandler($handler);
    }
    
    //New relic
    if (config('services.newrelic.enabled', false)) {
        $handler = new Monolog\Handler\NewRelicHandler(Monolog\Logger::ERROR, true, config('services.newrelic.app_name'));
        $monolog->pushHandler($handler);
    }
    
    //Loggly
    if (config('services.loggly.enabled', false)) {
        $key = config('services.loggly.token');
        $handler = new Monolog\Handler\LogglyHandler($key, Monolog\Logger::ERROR, true);
        $handler->setTag(config('services.loggly.tag'));
        $monolog->pushHandler($handler);
    }
});

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
