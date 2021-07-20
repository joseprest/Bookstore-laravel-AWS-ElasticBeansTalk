<?php namespace Manivelle\Providers;

use Panneau\Support\ServiceProvider;
use Manivelle\Services\Localizer;

class LocalizerServiceProvider extends ServiceProvider
{
    
    /**
     * Register
     *
     * @return void
     */
    public function register()
    {
        parent::register();
        
        $this->registerLocalizer();
    }
    
    /**
     * Register localizer
     *
     * @return void
     */
    protected function registerLocalizer()
    {
        $this->app->singleton('localizer', function ($app) {
            return new Localizer($app);
        });
    }
}
