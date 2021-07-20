<?php

/**
 * Maintenance
 */
Route::group(array(
    'prefix' => 'maintenance',
    'middleware' => array('web', 'auth')
), function () {
});
