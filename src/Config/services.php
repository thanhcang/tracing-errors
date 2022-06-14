<?php
return [
    'services.aws' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'ap-southeast-1'),
        'endpoint' => env('AWS_ENDPOINT'),
    ],
    'services.sentry' => [
        'dsn' => env('SENTRY_DSN')
    ]
];
