<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, SparkPost and others. This file provides a sane default
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],
    // 'firebase' => [
    //     'api_key' => 'API_KEY', // Only used for JS integration
    //     'auth_domain' => 'AUTH_DOMAIN', // Only used for JS integration
    //     'database_url' => 'https://lostschat.firebaseio.com',
    //     'secret' => '7H4msC6sCDrhfNs8u0CbcRYrqi2L7XmBCFxtzynh',
    //     'storage_bucket' => 'STORAGE_BUCKET', // Only used for JS integration
    // ]
    'firebase' => [
        'api_key' => 'API_KEY', // Only used for JS integration
        'auth_domain' => 'AUTH_DOMAIN', // Only used for JS integration
        'database_url' => 'https://raneem-a427d.firebaseio.com',
        'secret' => 'DErd5Sii34Bg2aDVBg2J2qfqxpn5LCC8AX71h7vY',
        'storage_bucket' => 'STORAGE_BUCKET', // Only used for JS integration
    ]
    

];
