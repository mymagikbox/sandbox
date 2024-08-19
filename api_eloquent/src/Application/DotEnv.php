<?php
declare(strict_types=1);

namespace PhpLab\Application;

$baseDir = __DIR__ . '/../../';
$dotenv = \Dotenv\Dotenv::createImmutable($baseDir);
if (file_exists($baseDir . '.env')) {
    $dotenv->load();
}
$dotenv->required([
    'IS_DEV_MODE',
    'ADMIN_URL',
    'ACCESS_TOKEN_SECRET',
    'REFRESH_TOKEN_SECRET',
    'DB_DRIVER',
    'DB_HOST',
    'DB_DATABASE',
    'DB_CHARSET',
    'DB_COLLATION',
    'DB_PORT',
    'DB_PREFIX',
    'DB_USERNAME',
    'DB_PASSWORD',
]);