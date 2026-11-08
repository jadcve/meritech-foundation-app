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

    /*
    | Vistas de muestra del sistema visual. No son un módulo de negocio.
    | En producción deben permanecer apagadas: no tienen autenticación.
    */
    'ui' => [
        'showcase' => env('FOUNDATION_UI_SHOWCASE', false),
        'verticals' => [
            'services' => [
                'label' => 'Servicios',
                'accent' => '#e11d48',
                'accent_secondary' => '#f59e0b',
                'mark' => 'SV',
            ],
            'health' => [
                'label' => 'Salud',
                'accent' => '#1d4ed8',
                'accent_secondary' => '#0ea5e9',
                'mark' => 'SA',
            ],
            'mechanic' => [
                'label' => 'Taller',
                'accent' => '#ea580c',
                'accent_secondary' => '#65a30d',
                'mark' => 'TL',
            ],
        ],
    ],
];
