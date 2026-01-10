<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'facebook' => [
        'client_id' => '1266127127090947',
        'client_secret' => '9515b01724585f9d1fb943685525f0ec',
        'redirect' => 'callback/facebook',
    ],
    'google' => [
        'client_id' => '932682567321-899t460n3ifb1ict648163i64iihpt5o.apps.googleusercontent.com',
        'client_secret' => '9JzLD9Sa2ZjhS_dLZCBo5745',
        'redirect' => 'callback/google',
    ],
    'apple' => [
        'client_id' => 'sland.vn',
        'client_secret' => 'eyJraWQiOiJZMzdDUVVDNVhBIiwiYWxnIjoiRVMyNTYifQ.eyJpc3MiOiJEWk1HQlA3N1JUIiwiaWF0IjoxNTk3Njc0MzYzLCJleHAiOjE2MTMyMjYzNjMsImF1ZCI6Imh0dHBzOi8vYXBwbGVpZC5hcHBsZS5jb20iLCJzdWIiOiJzbGFuZC52biJ9.C58uMiiTcfFGc-26mYHXNM0_qmiWyouT9grcaLtQgJshWj3_Pstjyvc4pNsKctlydekhj7PXGJNPIJAl3kPs_g',
        'redirect' => 'callback/apple',
    ],
];
