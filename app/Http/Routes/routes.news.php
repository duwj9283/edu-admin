<?php
Route::group(['prefix' => 'admin', 'namespace' => 'App\Http\Controllers\Admin'], function () {
    Route::get('newsclass', 'NewsclassController@getIndex');
    Route::get('newsclass/tree-list', 'NewsclassController@getTreeList');
    Route::get('newsclass/popedom', 'NewsclassController@getPopedom');
    Route::get('newsinfo', 'NewsinfoController@getIndex');
    Route::get('newsinfo/list/{class_id}', 'NewsinfoController@getList');
    Route::get('newsinfo/pics', 'NewsinfoController@getPics');
});

Route::group(['prefix' => 'api', 'namespace' => 'App\Http\Controllers\Api'], function () {
    Route::get('newsclass/list', 'NewsclassController@getList');
    Route::get('newsclass/info', 'NewsclassController@getInfo');
    Route::post('newsclass/insert', 'NewsclassController@postInsert');
    Route::post('newsclass/update', 'NewsclassController@postUpdate');
    Route::post('newsclass/delete', 'NewsclassController@postDelete');
    Route::post('newsclass/upload-pic1', 'NewsclassController@postUploadPic1');
    Route::post('newsclass/remove-pic1', 'NewsclassController@postRemovePic1');
    Route::post('newsclass/add-column', 'NewsclassController@postAddColumn');
    Route::post('newsclass/edit-column', 'NewsclassController@postEditColumn');
    Route::post('newsclass/add-popedom', 'NewsclassController@postAddPopedom');
    Route::post('newsclass/remove-popedom', 'NewsclassController@postRemovePopedom');

    Route::get('newsinfo/page-list', 'NewsinfoController@getPageList');
    Route::get('newsinfo/info', 'NewsinfoController@getInfo');
    Route::post('newsinfo/insert', 'NewsinfoController@postInsert');
    Route::post('newsinfo/update', 'NewsinfoController@postUpdate');
    Route::post('newsinfo/upload-pic1', 'NewsinfoController@postUploadPic1');
    Route::post('newsinfo/remove-pic1', 'NewsinfoController@postRemovePic1');
    Route::post('newsinfo/upload-pic2', 'NewsinfoController@postUploadPic2');
    Route::post('newsinfo/remove-pic2', 'NewsinfoController@postRemovePic2');
    Route::get('newsinfo/pics-info', 'NewsinfoController@getPicsInfo');
    Route::post('newsinfo/pics-insert', 'NewsinfoController@postPicsInsert');
    Route::post('newsinfo/pics-update', 'NewsinfoController@postPicsUpdate');
    Route::post('newsinfo/pics-delete', 'NewsinfoController@postPicsDelete');
    Route::post('newsinfo/upload-file1', 'NewsinfoController@postUploadFile1');
    Route::post('newsinfo/remove-file1', 'NewsinfoController@postRemoveFile1');
    Route::post('newsinfo/move', 'NewsinfoController@postMove');
    Route::post('newsinfo/delete', 'NewsinfoController@postDelete');
});
