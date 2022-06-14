<?php

return [
    'zipkin' => [
        'host' => env('ZIPKIN_HOST', 'localhost'),
        'port' => env('ZIPKIN_PORT', 9411),
        'options' => [
            '128bit' => false,
            'max_tag_len' => 1048576,
            'request_timeout' => 5,
        ],
    ],
    'tracing.excluded_paths' => [
        '/'
    ]
];
