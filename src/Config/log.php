<?php

use Psr\Log\LogLevel;

return [
    'log.displayErrorDetails' => true, // Should be set to false in production
    'log.logError' => false,
    'log.logErrorDetails' => false,
    'log.name' => '[Adapter][Inextia]',
    'log.level' => LogLevel::DEBUG,
    'log.path' => env('LOG_PATH', 'php://stdout'),
    'log.channel' => env('LOG_CHANNEL', 'default'),

    'log.aws.group' => env('LOG_GROUP'),
    'log.aws.group.default' => env('LOG_GROUP_DEFAULT'),
    'log.aws.stream' => env('LOG_STREAM'),
    'log.aws.retention' => 7,
    'log.aws.batchSize' => 10,
];
