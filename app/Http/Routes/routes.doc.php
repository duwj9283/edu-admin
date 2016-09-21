<?php
Route::group(['prefix' => 'api/doc', 'namespace' => 'App\Http\Controllers\Api'], function () {
    Route::get('docImg', 'DocController@getDocImg');
});