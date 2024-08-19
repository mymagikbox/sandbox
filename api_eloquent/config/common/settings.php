<?php
declare(strict_types=1);

// common settings
return [
    'config' => [
        'displayErrorDetails' => (bool)$_ENV['IS_DEV_MODE'], // Should be set to false in production
        'logError' => false,
        'logErrorDetails' => false,
        'timezone' => 'Europe/Moscow',
    ],
];