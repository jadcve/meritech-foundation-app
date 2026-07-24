<?php

return [
    'name' => env('FOUNDATION_NAME', 'Meritech Foundation'),
    'version' => '1.0.0',

    'auth' => [
        'provider' => 'laravel-breeze',

        'public_registration' => env(
            'FOUNDATION_PUBLIC_REGISTRATION',
            false,
        ),

        'stack' => [
            'views' => 'blade',
            'css' => 'tailwind',
            'interactivity' => 'alpine',
        ],
        'features' => [
            'login' => true,
            'logout' => true,
            'password_reset' => true,
            'password_update' => true,
            'email_verification' => true,
            'profile' => true,
            'authenticated_routes' => true,
        ],
    ],

    'tenancy' => [
        'enabled' => true,
        'fail_closed' => true,
        'bypass' => [
            'enabled' => false,
        ],
    ],
];
