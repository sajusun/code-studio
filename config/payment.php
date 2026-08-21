<?php

return [
    'default' => env('PAYMENT_DEFAULT_DRIVER', 'stripe'),

    'currency' => env('PAYMENT_DEFAULT_CURRENCY', 'USD'),

    'drivers' => [
        'stripe' => [
            'key' => env('STRIPE_KEY'),
            'secret' => env('STRIPE_SECRET'),
            'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        ],
        'sslcommerz' => [
            'store_id' => env('SSLCOMMERZ_STORE_ID'),
            'store_password' => env('SSLCOMMERZ_STORE_PASSWORD'),
            'is_sandbox' => (bool) env('SSLCOMMERZ_IS_SANDBOX', true),
        ],
        'bkash' => [
            'app_key' => env('BKASH_APP_KEY'),
            'app_secret' => env('BKASH_APP_SECRET'),
            'username' => env('BKASH_USERNAME'),
            'password' => env('BKASH_PASSWORD'),
            'is_sandbox' => (bool) env('BKASH_IS_SANDBOX', true),
        ],
        'chapa' => [
            'secret_key' => env('CHAPA_SECRET_KEY'),
        ],
        'paypal' => [
            'mode' => env('PAYPAL_MODE', 'sandbox'),
            'client_id' => env('PAYPAL_CLIENT_ID'),
            'client_secret' => env('PAYPAL_CLIENT_SECRET'),
        ],
    ],

    'success_url' => env('PAYMENT_SUCCESS_URL', '/payment/success'),
    'cancel_url' => env('PAYMENT_CANCEL_URL', '/payment/cancel'),
];
