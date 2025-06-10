<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Available Languages
    |--------------------------------------------------------------------------
    |
    | List of available languages for the application
    |
    */
    'available' => [
        'en' => [
            'name' => 'English',
            'native' => 'English',
            'flag' => '🇺🇸',
            'direction' => 'ltr'
        ],
        'si' => [
            'name' => 'Sinhala',
            'native' => 'සිංහල',
            'flag' => '🇱🇰',
            'direction' => 'ltr'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Language
    |--------------------------------------------------------------------------
    |
    | Default language for the application
    |
    */
    'default' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Fallback Language
    |--------------------------------------------------------------------------
    |
    | Fallback language when translation is not available
    |
    */
    'fallback' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Language Detection
    |--------------------------------------------------------------------------
    |
    | Detect language from various sources
    |
    */
    'detect_from' => [
        'session' => true,
        'cookie' => true,
        'header' => true,
        'user_preference' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cookie Settings
    |--------------------------------------------------------------------------
    |
    | Language cookie configuration
    |
    */
    'cookie' => [
        'name' => 'app_language',
        'expire' => 60 * 24 * 30, // 30 days
        'path' => '/',
        'domain' => null,
        'secure' => false,
        'httpOnly' => true,
    ],
];