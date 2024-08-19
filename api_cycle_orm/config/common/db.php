<?php
declare(strict_types=1);


use PhpLab\Application\Interface\Settings\SettingsInterface;
use PhpLab\Domain\Helper\ArrayHelper;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

use Symfony\Component\Finder\Finder;

use Cycle\ORM;
use Cycle\ORM\EntityManager;
use Cycle\Database\DatabaseManager;
use Cycle\Database\Config;
use Cycle\Database\DatabaseProviderInterface;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\Collection\DoctrineCollectionFactory;
use Cycle\ORM\EntityManagerInterface;

$isDevMode = (bool) $_ENV['IS_DEV_MODE'];

return [
    DatabaseProviderInterface::class => function (ContainerInterface $c) {
        $settings = $c->get(SettingsInterface::class);
        $logger = $c->get(LoggerInterface::class);

        $dbSettings = $settings->get('db');

        $config = ArrayHelper::merge($dbSettings['cycle_orm'], [
            'connections' => [
                'mysql' => new Config\MySQLDriverConfig(
                    connection: new Config\MySQL\TcpConnectionConfig(
                        database: $_ENV['DB_DATABASE'],
                        host: $_ENV['DB_HOST'],
                        port: (int) $_ENV['DB_PORT'],
                        charset: $_ENV['DB_CHARSET'],
                        user: $_ENV['DB_USERNAME'],
                        password: $_ENV['DB_PASSWORD'],
                    ),
                    queryCache: true
                )
            ]
        ]);

        $dbal = new DatabaseManager(
            new Config\DatabaseConfig($config)
        );

        $dbal->setLogger($logger);

        return $dbal;
    },

    ORMInterface::class => function (ContainerInterface $c) {
        $dbal = $c->get(DatabaseProviderInterface::class);
        $settings = $c->get(SettingsInterface::class);
        $dbSettings = $settings->get('db');

        $schema = [];

        // https://symfony.com/doc/current/components/finder.html
        $finder = new Finder();
        $finder
            ->files()
            ->name('*.php')
            ->in($dbSettings['schema_mapping_list']);

        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $modelSchema = require_once $file->getRealPath();

                if(is_array($modelSchema) && count($modelSchema)) {
                    $schema = ArrayHelper::merge($schema, $modelSchema);
                }
            }
        }


        return new ORM\ORM(
            new ORM\Factory($dbal, null, null, new DoctrineCollectionFactory()),
            new ORM\Schema($schema)
        );
    },

    EntityManagerInterface::class => function (ContainerInterface $c) {
        $orm = $c->get(ORMInterface::class);

        return new EntityManager($orm);
    },

    'config' => [
        'db' => [
            'is_dev_mode' => $isDevMode,

            'cycle_orm' => [
                'default' => 'default',
                'databases' => [
                    'default' => [
                        'connection' => 'mysql'
                    ]
                ],
            ],

            'schema_mapping_list' => [
                ROOT_DIR . '/src/Modules/*/Infrastructure/Database/Mapping'
            ],

        ],
    ],

];