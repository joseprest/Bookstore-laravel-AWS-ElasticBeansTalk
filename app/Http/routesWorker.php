<?php

Route::group([
    'prefix' => 'worker'
], function () {
    /*
    |--------------------------------------------------------------------------
    | Receive queue job
    |--------------------------------------------------------------------------
    */
    Route::any('/queue/receive', [
        'as' => 'worker.queue',
        'uses' => 'WorkerController@queue'
    ]);

    /*
    |--------------------------------------------------------------------------
    | Execute schedule
    |--------------------------------------------------------------------------
    */
    Route::any('/scheduler', [
        'as' => 'worker.scheduler',
        'uses' => 'WorkerController@scheduler'
    ]);
});
