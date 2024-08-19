<?php
declare(strict_types=1);

namespace PhpLab\Application;

$dotenv = \Dotenv\Dotenv::createImmutable(ROOT_DIR);
if (file_exists(ROOT_DIR . '/.env')) {
    $dotenv->load();
}
$dotenv->required([
    'IS_DEV_MODE',
    'ADMIN_URL',
    'ACCESS_TOKEN_SECRET',
    'REFRESH_TOKEN_SECRET',
    'PASSWORD_SALT',
    'DB_HOST',
    'DB_DATABASE',
    'DB_CHARSET',
    'DB_PORT',
    'DB_USERNAME',
    'DB_PASSWORD',
]);