<?php

return [
    'login_url' => env('CELCREDIT_LOGIN_URL', 'https://sandbox.auth.flowfinance.com.br/oauth2/token'),
    'api_url' => env('CELCREDIT_API_URL', 'https://sandbox.platform.flowfinance.com.br'),
    'originator_id' => env('CELCREDIT_ORIGINATOR_ID', null),
    'originator_client_id' => env('CELCREDIT_ORIGINATOR_CLIENT_ID', null),
    'originator_client_secret' => env('CELCREDIT_ORIGINATOR_CLIENT_SECRET', null),
    'product_id' => env('CELCREDIT_PRODUCT_ID', null),
    'funding' => [
        'default' => [
            'id' => env('CELCREDIT_FUNDING_DEFAULT_ID'),
            'client_id' => env('CELCREDIT_FUNDING_DEFAULT_CLIENT_ID'),
            'client_secret' => env('CELCREDIT_FUNDING_DEFAULT_CLIENT_SECRET'),
        ],
    ],
];
