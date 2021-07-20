<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/**
 * Site root
 */
Route::get('/', array(
    'domain' => config('app.domains.default'),
    'middleware' => array('web'),
    'uses' => 'RootController@index'
));

/**
 * Routes that can be localized
 */
foreach (Localizer::getAllLocales() as $locale) {
    Route::group([
        'prefix' => $locale,
        'locale' => $locale
    ], function () use ($locale) {

        /**
         * Home
         */
        Route::get('/', array(
            'domain' => config('app.domains.default'),
            'as' => Localizer::routeName('home', $locale),
            'middleware' => array('web'),
            'uses' => 'HomeController@index'
        ));

        // Password Reset Routes...
        Route::group(array(
            'domain' => config('app.domains.default'),
            'prefix' => trans('routes.auth', [], 'messages', $locale),
            'middleware' => array('web'),
            'namespace' => 'Auth'
        ), function () use ($locale) {
            Route::get(trans('routes.auth.reset_form', [], 'messages', $locale), [
                'as' => Localizer::routeName('auth.reset.form', $locale),
                'uses' => 'PasswordController@showResetForm',
            ]);
            Route::post(trans('routes.auth.email', [], 'messages', $locale), [
                'as' => Localizer::routeName('auth.reset.email', $locale),
                'uses' => 'PasswordController@sendResetLinkEmail',
            ]);
            Route::post(trans('routes.auth.reset', [], 'messages', $locale), [
                'as' => Localizer::routeName('auth.reset', $locale),
                'uses' => 'PasswordController@reset',
            ]);
        });

        /**
         * Account
         */
        Route::group(array(
            'domain' => config('app.domains.default'),
            'prefix' => trans('routes.account', [], 'messages', $locale),
            'middleware' => array('web', 'auth'),
            'namespace' => 'Account'
        ), function () use ($locale) {
            Route::get('/', array(
                'as' => Localizer::routeName('account', $locale),
                'uses' => 'AccountController@index'
            ));

            Route::post('/', array(
                'as' => Localizer::routeName('account.update', $locale),
                'uses' => 'AccountController@update'
            ));

            Route::delete('/', array(
                'as' => Localizer::routeName('account.delete', $locale),
                'uses' => 'AccountController@delete'
            ));
        });

        /**
         * Admin
         */
        Route::group(array(
            'domain' => config('app.domains.default'),
            'prefix' => trans('routes.admin', [], 'messages', $locale),
            'middleware' => array('web', 'auth', 'auth.role:admin'),
            'namespace' => 'Admin'
        ), function () use ($locale) {
            Route::get('/', array(
                'as' => Localizer::routeName('admin', $locale),
                'uses' => 'AdminController@index'
            ));

            Route::resource('users', '\Manivelle\Http\Controllers\Admin\UsersController', [
                'name' => trans('routes.users', [], 'messages', $locale),
                'names' => [
                    'index' => Localizer::routeName('admin.users.index', $locale),
                    'show' => Localizer::routeName('admin.users.show', $locale),
                    'edit' => Localizer::routeName('admin.users.edit', $locale),
                    'update' => Localizer::routeName('admin.users.update', $locale),
                    'create' => Localizer::routeName('admin.users.create', $locale),
                    'store' => Localizer::routeName('admin.users.store', $locale),
                    'destroy' => Localizer::routeName('admin.users.destroy', $locale)
                ]
            ]);

            Route::resource('organisations', '\Manivelle\Http\Controllers\Admin\OrganisationsController', [
                'name' => trans('routes.organisations', [], 'messages', $locale),
                'names' => [
                    'index' => Localizer::routeName('admin.organisations.index', $locale),
                    'show' => Localizer::routeName('admin.organisations.show', $locale),
                    'edit' => Localizer::routeName('admin.organisations.edit', $locale),
                    'update' => Localizer::routeName('admin.organisations.update', $locale),
                    'create' => Localizer::routeName('admin.organisations.create', $locale),
                    'store' => Localizer::routeName('admin.organisations.store', $locale),
                    'destroy' => Localizer::routeName('admin.organisations.destroy', $locale)
                ]
            ]);

            Route::get(trans('routes.importations', [], 'messages', $locale), [
                'as' => Localizer::routeName('admin.importations.index', $locale),
                'uses' => 'ImportationsController@index'
            ]);

            Route::get(trans('routes.importations.source', [], 'messages', $locale), [
                'as' => Localizer::routeName('admin.importations.source', $locale),
                'uses' => 'ImportationsController@source'
            ]);

            Route::get(trans('routes.importations.source.editLibraries', [], 'messages', $locale), [
                'as' => Localizer::routeName('admin.importations.source.editLibraries', $locale),
                'uses' => 'ImportationsController@editLibraries'
            ]);

            Route::post(trans('routes.importations.source.editLibraries', [], 'messages', $locale), [
                'as' => Localizer::routeName('admin.importations.source.saveLibraries', $locale),
                'uses' => 'ImportationsController@saveLibraries'
            ]);
        });

        /**
         * GraphQL
         */
        Route::any('/graphql/query', array(
            'domain' => config('app.domains.default'),
            'as' => Localizer::routeName('graphql.query', $locale),
            'middleware' => array('web', 'auth'),
            'uses' => '\Manivelle\Http\Controllers\GraphQLController@query'
        ));
    });
}

require(__DIR__.'/routesOrganisation.php');
require(__DIR__.'/routesScreen.php');
require(__DIR__.'/routesApi.php');
require(__DIR__.'/routesMaintenance.php');
