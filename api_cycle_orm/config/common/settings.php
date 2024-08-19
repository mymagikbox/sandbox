<?php
declare(strict_types=1);

$mode = (bool) $_ENV['IS_DEV_MODE'];

// common settings
return [
    'config' => [
        'displayErrorDetails' => $mode, // Should be set to false in production
        'logError' => true,
        'logErrorDetails' => true,
        'timezone' => 'Europe/Moscow',
    ],
];