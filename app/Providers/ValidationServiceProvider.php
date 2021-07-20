<?php namespace Manivelle\Providers;

use Illuminate\Support\ServiceProvider;
use Blade;
use Validator;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('user_not_in_organisation', '\Manivelle\Validation\UserNotInOrganisation@validate');
        Validator::extend('not_already_invited', '\Manivelle\Validation\NotAlreadyInvited@validate');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
