<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
     */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    /**
     * 前台项目访问路径及站点主目录
     */
    'frontend' => [
        'url' => env('FRONTEND_URL', 'http://lubo.iemaker.cn/'),
        'path' => env('FRONTEND_PATH', '/home/debian/www/lubov2/public/'),
    ],
    /**
     * 网络视频流支持服务
     */
    'vs2_serv' => [
        'host' => 'lubo.iemaker.cn',
        'port' => 1998,
        'app_name' => 'myapp',
    ],
];
