<?php

/**
 * Organisation
 */

/**
 * Routes that can be localized
 */
Route::group(array(
    'domain' => config('app.domains.organisation'),
    'middleware' => array('web', 'organisation'),
    'namespace' => 'Organisation'
), function () {

    /**
     * Authenticated but non-localized routes
     */
    Route::group(array(
        'middleware' => array('auth', 'auth.organisation')
    ), function () {
        /**
         * Site root
         */
        Route::get('/', array(
            'as' => 'organisation.root',
            'uses' => 'RootController@index'
        ));
    });

    /**
     * Localized routes
     */
    foreach (Localizer::getAllLocales() as $locale) {
        Route::group([
            'prefix' => $locale,
            'locale' => $locale
        ], function () use ($locale) {

            /**
             * Invitation
             */
            Route::get('/invitation/{invitation}', array(
               'as' => Localizer::routeName('organisation.invitation', $locale),
               'uses' => 'InvitationController@index'
            ));

            Route::put('/invitation/{invitation}', array(
              'as' => Localizer::routeName('organisation.invitation.store', $locale),
              'uses' => 'InvitationController@store'
            ));

            Route::post('/invitation/{invitation}', array(
              'as' => Localizer::routeName('organisation.invitation.link', $locale),
              'uses' => 'InvitationController@link'
            ));

            //Authenticated
            Route::group(array(
                'middleware' => array('auth', 'auth.organisation')
            ), function () use ($locale) {
                Route::get('/', array(
                    'as' => Localizer::routeName('organisation.home', $locale),
                    'uses' => 'OrganisationController@index'
                ));

                Route::get(trans('routes.edit', [], 'messages', $locale), array(
                    'as' => Localizer::routeName('organisation.edit', $locale),
                    'uses' => 'OrganisationController@edit'
                ));

                Route::post('/', array(
                    'as' => Localizer::routeName('organisation.update', $locale),
                    'uses' => 'OrganisationController@update'
                ));

                /**
                 * Screens
                 */
                Route::group([
                    'middleware' => array('screen'),
                ], function () use ($locale) {
                    Route::resource('screens', '\Manivelle\Http\Controllers\Organisation\ScreensController', [
                        'name' => trans('routes.screens', [], 'messages', $locale),
                        'except' => ['index', 'edit', 'create', 'destroy'],
                        'names' => array(
                            'store' => Localizer::routeName('organisation.screens.store', $locale),
                            'show' => Localizer::routeName('organisation.screens.show', $locale),
                            'update' => Localizer::routeName('organisation.screens.update', $locale)
                        )
                    ]);

                    Route::get(trans('routes.screens.slideshow', [], 'messages', $locale), array(
                        'as' => Localizer::routeName('organisation.screens.slideshow', $locale),
                        'uses' => 'ScreensController@show'
                    ));

                    Route::get(trans('routes.screens.notifications', [], 'messages', $locale), array(
                        'as' => Localizer::routeName('organisation.screens.notifications', $locale),
                        'uses' => 'ScreensController@show'
                    ));

                    Route::get(trans('routes.screens.channels', [], 'messages', $locale), array(
                        'as' => Localizer::routeName('organisation.screens.channels', $locale),
                        'uses' => 'ScreensController@show'
                    ));

                    Route::get(trans('routes.screens.channel', [], 'messages', $locale), array(
                        'as' => Localizer::routeName('organisation.screens.channel', $locale),
                        'uses' => 'ScreensController@channel'
                    ));

                    Route::post(trans('routes.screens.channel.settings', [], 'messages', $locale), array(
                        'as' => Localizer::routeName('organisation.screens.channel.update', $locale),
                        'uses' => 'ScreensController@channel_settings_update'
                    ));

                    Route::get(trans('routes.screens.settings', [], 'messages', $locale), array(
                        'as' => Localizer::routeName('organisation.screens.settings', $locale),
                        'uses' => 'ScreensController@show'
                    ));

                    Route::get(trans('routes.screens.controls', [], 'messages', $locale), array(
                        'as' => Localizer::routeName('organisation.screens.controls', $locale),
                        'uses' => 'ScreensController@show'
                    ));

                    Route::get(trans('routes.screens.stats', [], 'messages', $locale), array(
                        'as' => Localizer::routeName('organisation.screens.stats', $locale),
                        'uses' => 'ScreensController@show'
                    ));

                    Route::post(trans('routes.screens.link', [], 'messages', $locale), array(
                        'as' => Localizer::routeName('organisation.screens.link', $locale),
                        'uses' => 'ScreensController@link'
                    ));

                    Route::delete(trans('routes.screens.unlink', [], 'messages', $locale), array(
                        'as' => Localizer::routeName('organisation.screens.unlink', $locale),
                        'uses' => 'ScreensController@unlink'
                    ));
                });

                /**
                 * Bubbles
                 */
                Route::resource('bubbles', '\Manivelle\Http\Controllers\Organisation\BubblesController', [
                    'name' => trans('routes.screens.channels.bubbles', [], 'messages', $locale),
                    'names' => array(
                        'index' => Localizer::routeName('organisation.bubbles.index', $locale),
                        'show' => Localizer::routeName('organisation.bubbles.show', $locale),
                        'edit' => Localizer::routeName('organisation.bubbles.edit', $locale),
                        'update' => Localizer::routeName('organisation.bubbles.update', $locale),
                        'create' => Localizer::routeName('organisation.bubbles.create', $locale),
                        'store' => Localizer::routeName('organisation.bubbles.store', $locale),
                        'destroy' => Localizer::routeName('organisation.bubbles.destroy', $locale)
                    )
                ]);

                /**
                 * Team
                 */
                Route::resource('team', '\Manivelle\Http\Controllers\Organisation\TeamController', [
                    'name' => trans('routes.team', [], 'messages', $locale),
                    'names' => array(
                        'index' => Localizer::routeName('organisation.team.index', $locale),
                        'show' => Localizer::routeName('organisation.team.show', $locale),
                        'edit' => Localizer::routeName('organisation.team.edit', $locale),
                        'update' => Localizer::routeName('organisation.team.update', $locale),
                        'create' => Localizer::routeName('organisation.team.create', $locale),
                        'store' => Localizer::routeName('organisation.team.store', $locale),
                        'destroy' => Localizer::routeName('organisation.team.destroy', $locale)
                    )
                ]);

                Route::post(trans('routes.team.invite', [], 'messages', $locale), array(
                    'as' => Localizer::routeName('organisation.team.invite', $locale),
                    'uses' => 'TeamController@invite'
                ));

                Route::any('/graphql/query', array(
                    'as' => Localizer::routeName('organisation.graphql.query', $locale),
                    'uses' => '\Manivelle\Http\Controllers\GraphQLController@query'
                ));
            });
        });
    }
});
