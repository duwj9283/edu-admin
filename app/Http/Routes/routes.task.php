<?php
Route::group(['prefix' => 'api/task', 'namespace' => 'App\Http\Controllers\Api'], function () {
    Route::get('queue', 'TaskController@getQueue');
    Route::get('queue2', 'TaskController@getQueue2');
    Route::get('callback', 'TaskController@getCallback');
});
