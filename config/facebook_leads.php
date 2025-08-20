<?php

return [
    'accounts' => [
        'account1' => [
            'app_id' => env('FACEBOOK_ACCOUNT1_APP_ID', ''),
            'app_secret' => env('FACEBOOK_ACCOUNT1_APP_SECRET', ''),
            'access_token' => env('FACEBOOK_ACCOUNT1_ACCESS_TOKEN', ''),
            'ad_account_id' => env('FACEBOOK_ACCOUNT1_AD_ACCOUNT_ID', ''),
        ],
        'account2' => [
            'app_id' => env('FACEBOOK_ACCOUNT2_APP_ID', ''),
            'app_secret' => env('FACEBOOK_ACCOUNT2_APP_SECRET', ''),
            'access_token' => env('FACEBOOK_ACCOUNT2_ACCESS_TOKEN', ''),
            'ad_account_id' => env('FACEBOOK_ACCOUNT2_AD_ACCOUNT_ID', ''),
        ],
    ],
    'api_version' => env('FACEBOOK_API_VERSION', 'v19.0'),
    'security_token' => env('FACEBOOK_SECURITY_TOKEN', ''),
];
