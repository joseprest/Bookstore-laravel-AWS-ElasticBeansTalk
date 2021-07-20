<?php

/**
 * Screen
 */
Route::group(array(
    'namespace' => 'Screen',
    'domain' => config('app.domains.screen'),
    'middleware' => array('web')
), function () {
    
    Route::get('/', [
        'as' => 'screen.home',
        'uses' => 'ScreenController@index'
    ]);
    
    Route::group(array(
        'prefix' => 'data'
    ), function () {
    
        Route::get('/version.json', [
            'as' => 'screen.data.version',
            'uses' => 'DataController@version'
        ]);
        
        Route::get('/screen.json', [
            'as' => 'screen.data.screen',
            'uses' => 'DataController@screen'
        ]);
    
        Route::get('/bubbles.json', [
            'as' => 'screen.data.bubbles',
            'uses' => 'DataController@bubbles'
        ]);
    
        Route::get('/bubbles/{bubble_id}.json', [
            'as' => 'screen.data.bubble',
            'uses' => 'DataController@bubble'
        ]);
        
        Route::get('/bubbles/page/{page}.json', [
            'as' => 'screen.data.bubble_page',
            'uses' => 'DataController@bubble_page'
        ]);
        
        Route::get('/bubbles/page/{count}/{page}.json', [
            'as' => 'screen.data.bubble_page_count',
            'uses' => 'DataController@bubble_page'
        ]);
        
        Route::get('/channels.json', [
            'as' => 'screen.data.channels',
            'uses' => 'DataController@channels'
        ]);
        
        Route::get('/timeline.json', [
            'as' => 'screen.data.timeline',
            'uses' => 'DataController@timeline'
        ]);
    });
});

Route::group(array(
    'namespace' => 'Screen',
    'domain' => config('app.domains.screen'),
    'prefix' => 'api',
    'middleware' => array('api', 'cors')
), function () {
    
    Route::post('/screen/update', [
        'as' => 'screen.api.screen_update',
        'uses' => 'ApiController@screen_update'
    ]);

    Route::post('/share/message', [
        'as' => 'screen.api.share_message',
        'uses' => 'ApiController@share_message'
    ]);

    Route::get('/share/message/test', [
        'as' => 'screen.api.test_message',
        'uses' => 'ApiController@test_message'
    ]);

    Route::post('/share/email', [
        'as' => 'screen.api.share_email',
        'uses' => 'ApiController@share_email'
    ]);

    Route::post('/share/sms', [
        'as' => 'screen.api.share_sms',
        'uses' => 'ApiController@share_sms'
    ]);
});
