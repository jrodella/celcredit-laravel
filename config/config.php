<?php

return [
    'login_url' => env('CELCREDIT_LOGIN_URL', 'https://sandbox.auth.flowfinance.com.br/oauth2/token'),
    'api_url' => env('CELCREDIT_API_URL', 'https://sandbox.platform.flowfinance.com.br'),
    // 'mtls_cert_path' => env('CELCREDIT_MTLS_CERT_PATH', null),
    // 'mtls_key_path' => env('CELCREDIT_MTLS_KEY_PATH', null),
    // 'mtls_passphrase' => env('CELCREDIT_MTLS_PASSPHRASE', null),
    'originator_id' => env('CELCREDIT_ORIGINATOR_ID', null),
    'originator_client_id' => env('CELCREDIT_ORIGINATOR_CLIENT_ID', null),
    'originator_client_secret' => env('CELCREDIT_ORIGINATOR_CLIENT_SECRET', null),
    'funding_id' => env('CELCREDIT_FUNDING_ID', null),
    'funding_client_id' => env('CELCREDIT_FUNDING_CLIENT_ID', null),
    'funding_client_secret' => env('CELCREDIT_FUNDING_CLIENT_SECRET', null),
    'product_id' => env('CELCREDIT_PRODUCT_ID', null),
];
