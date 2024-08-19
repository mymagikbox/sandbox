<?php

require __DIR__ . '/src/Application/DotEnv.php';

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/src/Infrastructure/Db/Migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/src/Infrastructure/Db/Seeds'
    ],
    'templates' => [
        'file' => '%%PHINX_CONFIG_DIR%%/src/Infrastructure/Db/Migrations/template/Migration.up_down.template.php.dist',
    ],
    'environments' => [
        'default_migration_table' => 'migrations',
        'default_environment' => 'production',
        'production' => [
            'adapter' => $_ENV['DB_DRIVER'],
            'host' => $_ENV['DB_HOST'],
            'name' => $_ENV['DB_DATABASE'],
            'user' => $_ENV['DB_USERNAME'],
            'pass' => $_ENV['DB_PASSWORD'],
            'port' => $_ENV['DB_PORT'],
            'charset' => $_ENV['DB_CHARSET'],
            'collation' => $_ENV['DB_COLLATION'],
        ],
    ],
    'version_order' => 'creation'
];
