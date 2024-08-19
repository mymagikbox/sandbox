<?php
declare(strict_types=1);

use PhpLab\Application\DB;
use Psr\Container\ContainerInterface;

return [
    DB::class => function (ContainerInterface $c) {
        return new DB($c);
    },

    'config' => [
        'database' => [
            'driver'    => $_ENV['DB_DRIVER'],
            'host'      => $_ENV['DB_HOST'],
            'database'  => $_ENV['DB_DATABASE'],
            'username'  => $_ENV['DB_USERNAME'],
            'password'  => $_ENV['DB_PASSWORD'],
            'charset'   => $_ENV['DB_CHARSET'],
            'collation' => $_ENV['DB_COLLATION'],
            'port'      => $_ENV['DB_PORT'],
            'prefix'    => $_ENV['DB_PREFIX'],
        ],
    ],
];