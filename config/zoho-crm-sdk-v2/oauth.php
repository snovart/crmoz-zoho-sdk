<?php

return [
    'defaultClient' => env('ZOHO_CRM_CLIENT_ID',''),
    'hosts' => [
        'com' => 'https://accounts.zoho.com/oauth/v2/',
        'eu' => 'https://accounts.zoho.eu/oauth/v2/',
        'in' => 'https://accounts.zoho.in/oauth/v2/',
        'au' => 'https://accounts.zoho.com.au/oauth/v2/',
        'cn' => 'https://accounts.zoho.com.cn/oauth/v2/',
    ],
    'auth_hosts' => [
        'com' => 'com',
        'eu' => 'eu',
        'in' => 'in',
        'au' => 'au',
        'cn' => 'cn'
    ],
    'location_hosts' => [
        'us' => 'com',
        'eu' => 'eu',
        'in' => 'in',
        'au' => 'au',
        'cn' => 'cn'
    ],
    'saved_listener' => \ZohoCrmSDK\Listeners\SavedOAuthZohoListener::class,
    'model_company' => 'App\\Models\\Company',
    'storage' => env('ZOHO_SDK_OAUTH_STORAGE','db'),
    'encrypting' => false,
    'clientCRMOZ' => env('ZOHO_CRM_CLIENT_ID_CRMOZ','')
];