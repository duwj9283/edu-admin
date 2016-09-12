<?php
Route::group(['prefix' => 'api/task', 'namespace' => 'App\Http\Controllers\Api'], function () {
    Route::get('queue', 'TaskController@getQueue');
    Route::get('callback', 'TaskController@getCallback');
});
