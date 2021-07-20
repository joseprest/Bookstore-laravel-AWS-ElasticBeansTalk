<?php

/**
 * Api
 */
Route::group(array(
    'domain' => config('app.domains.api'),
    'namespace' => 'Api',
    'middleware' => ['api', 'cors']
), function () {
    Route::get('/token', array(
        'as' => 'api.token',
        'uses' => 'IndexController@token'
    ));

    Route::any('/graphql', array(
        'as' => 'api.graphql',
        'uses' => '\Manivelle\Http\Controllers\GraphQLController@query'
    ));

    /**
     * Screen
     */
    Route::group(array(
        'prefix' => '/screen',
        'middleware' => ['api', 'cors']
    ), function () {

        Route::get('/linked', array(
            'as' => 'api.screen.linked',
            'uses' => 'ScreenController@linked'
        ));

        Route::get('/get', array(
            'as' => 'api.screen.authenticate',
            'uses' => 'ScreenController@authenticate'
        ));

        Route::post('/ping', array(
            'as' => 'api.screen.ping',
            'uses' => 'ScreenController@ping'
        ));

        Route::post('/command/{command_id}', array(
            'as' => 'api.screen.command',
            'uses' => 'ScreenController@command'
        ));

        Route::post('/create', array(
            'as' => 'api.screen.create',
            'uses' => 'ScreenController@create'
        ));

        Route::post('/save', array(
            'as' => 'api.screen.save',
            'uses' => 'ScreenController@save'
        ));
    });

    /**
     * Screen
     */
    Route::group(array(
        'prefix' => '/channel',
        'middleware' => ['api', 'cors']
    ), function () {

        Route::get('{id}/csv', array(
            'as' => 'api.channel.csv',
            'uses' => 'ChannelController@csv'
        ));
    });

    /**
     * Share
     */
    Route::group(array(
        'prefix' => '/share',
        'middleware' => ['api', 'cors']
    ), function () {
        Route::post('/email', array(
            'as' => 'api.share.email',
            'uses' => 'ShareController@email'
        ));

        Route::post('/sms', array(
            'as' => 'api.share.sms',
            'uses' => 'ShareController@sms'
        ));
    });
});
