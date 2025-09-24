<?php

return [
    // ... other services

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    'politala_sso' => [
        'base_url' => env('POLITALA_SSO_URL'),
        'client_id' => env('POLITALA_SSO_CLIENT_ID'),
        'client_secret' => env('POLITALA_SSO_CLIENT_SECRET'),
        'redirect_uri' => env('POLITALA_SSO_REDIRECT'),
    ],
];