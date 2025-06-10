<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Payment Gateway
    |--------------------------------------------------------------------------
    |
    | Default payment gateway to use for transactions
    |
    */
    'default' => env('PAYMENT_GATEWAY', 'stripe'),

    /*
    |--------------------------------------------------------------------------
    | Payment Gateways
    |--------------------------------------------------------------------------
    |
    | Configuration for different payment gateways
    |
    */
    'gateways' => [
        'stripe' => [
            'public_key' => env('STRIPE_PUBLIC_KEY'),
            'secret_key' => env('STRIPE_SECRET_KEY'),
            'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
            'currency' => env('STRIPE_CURRENCY', 'USD'),
            'enabled' => env('STRIPE_ENABLED', true),
        ],

        'paypal' => [
            'client_id' => env('PAYPAL_CLIENT_ID'),
            'client_secret' => env('PAYPAL_CLIENT_SECRET'),
            'mode' => env('PAYPAL_MODE', 'sandbox'), // sandbox or live
            'currency' => env('PAYPAL_CURRENCY', 'USD'),
            'enabled' => env('PAYPAL_ENABLED', false),
        ],

        'razorpay' => [
            'key_id' => env('RAZORPAY_KEY_ID'),
            'key_secret' => env('RAZORPAY_KEY_SECRET'),
            'currency' => env('RAZORPAY_CURRENCY', 'INR'),
            'enabled' => env('RAZORPAY_ENABLED', false),
        ],

        // Simulated payment for testing
        'simulate' => [
            'enabled' => env('SIMULATE_PAYMENT', false),
            'success_rate' => env('SIMULATE_SUCCESS_RATE', 0.95), // 95% success rate
            'delay' => env('SIMULATE_DELAY', 2), // 2 seconds delay
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Currency Settings
    |--------------------------------------------------------------------------
    |
    | Default currency and supported currencies
    |
    */
    'currency' => [
        'default' => env('DEFAULT_CURRENCY', 'USD'),
        'symbol' => env('CURRENCY_SYMBOL', '$'),
        'position' => env('CURRENCY_POSITION', 'before'), // before or after
        'thousands_separator' => env('THOUSANDS_SEPARATOR', ','),
        'decimal_separator' => env('DECIMAL_SEPARATOR', '.'),
        'decimal_places' => env('DECIMAL_PLACES', 2),
    ],

    /*
    |--------------------------------------------------------------------------
    | Tax Settings
    |--------------------------------------------------------------------------
    |
    | Tax configuration
    |
    */
    'tax' => [
        'enabled' => env('TAX_ENABLED', true),
        'rate' => env('TAX_RATE', 0.10), // 10%
        'inclusive' => env('TAX_INCLUSIVE', false),
        'name' => env('TAX_NAME', 'VAT'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Shipping Settings
    |--------------------------------------------------------------------------
    |
    | Shipping configuration
    |
    */
    'shipping' => [
        'enabled' => env('SHIPPING_ENABLED', true),
        'free_threshold' => env('FREE_SHIPPING_THRESHOLD', 100),
        'default_cost' => env('DEFAULT_SHIPPING_COST', 10),
        'calculation_method' => env('SHIPPING_CALCULATION', 'flat'), // flat, weight, zone
    ],
];