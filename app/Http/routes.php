<?php
Route::group(['name' => 'home'], function () {
    Route::get('/', 'WelcomeController@getIndex');
});

Route::group(['prefix' => 'admin'], function () {
    Route::get('/', 'Admin\WelcomeController@getIndex');
    Route::get('register', 'Admin\WelcomeController@getRegister');
    Route::get('forgot', 'Admin\WelcomeController@getForgot');
    Route::get('logout', 'Api\AccountController@postLogout');
    Route::controllers([
        'entrust' => 'Admin\EntrustController', // RBAC
        'profile' => 'Admin\ProfileController', // 个人资料管理
        'subject' => 'Admin\SubjectController', //学科管理
        'capacity' => 'Admin\CapacityController', //容量申请管理
        'file' => 'Admin\FileController', //容量申请管理
        'apptype' => 'Admin\AppTypeController', //应用类型管理
        'webuser' => 'Admin\WebUserController', //教师用户管理
        'message' => 'Admin\MessageController', // 消息管理
        'device' => 'Admin\DeviceController', //编码器设备管理
        'sitecfg' => 'Admin\SiteconfigController', //站点配置
        'mkapp' => 'Admin\MkappController', //应用管理
    ]);
});

Route::group(['prefix' => 'api'], function () {
    Route::controllers([
        'account' => 'Api\AccountController',
        'user' => 'Api\UserController',
        'entrust' => 'Api\EntrustController',
        'subject' => 'Api\SubjectController',
    ]);
});

Route::any('ueditor', 'UEditorController@index');
