<?php

return [
    'default' => env('MAIL_MAILER', 'resend'),

    'mailers' => [
        'resend' => [
            'transport' => 'resend',
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],

        'failover' => [
            'transport' => 'failover',
            'mailers' => [
                'resend',
                'log',
            ],
        ],
    ],

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'info@youextractor.me'),
        'name' => env('MAIL_FROM_NAME', 'YouExtractor'),
    ],
];
