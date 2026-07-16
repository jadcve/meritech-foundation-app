<?php

return [
    'name' => env('FOUNDATION_NAME', 'Meritech Foundation'),
    'version' => '1.0.0',

    'auth' => [
        'provider' => 'laravel-breeze',
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
];
