<?php
declare(strict_types=1);

use PhpLab\Application\Interface\Settings\SettingsInterface;

use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Configuration\Migration\ConfigurationArray;
use Doctrine\ORM\Mapping\Driver\SimplifiedXmlDriver;

$isDevMode = (bool) $_ENV['IS_DEV_MODE'];

return [

    DependencyFactory::class => function (ContainerInterface $c) {
        $settings = $c->get(SettingsInterface::class);
        $logger = $c->get(LoggerInterface::class);

        $dbSettings = $settings->get('db');

        // $migrationConfig = new PhpFile('migrations.php');
        $migrationConfig = new ConfigurationArray($dbSettings['migration']);

        // Use the ArrayAdapter or the FilesystemAdapter depending on the value of the 'dev_mode' setting
        // You can substitute the FilesystemAdapter for any other cache you prefer from the symfony/cache library
        $cache = $dbSettings['is_dev_mode'] ?
            new ArrayAdapter() :
            new FilesystemAdapter(directory: $dbSettings['cache_dir']);

        $ORMConfig = ORMSetup::createConfiguration($dbSettings['is_dev_mode'], $dbSettings['proxy_dir'], $cache);

        $driver = new SimplifiedXmlDriver($dbSettings['metadata_dirs']);
        $driver->setGlobalBasename('global'); // global.orm.xml

        $ORMConfig->setMetadataDriverImpl($driver);

        /*$ORMConfig = ORMSetup::createAttributeMetadataConfiguration(
            $dbSettings['metadata_dirs'],
            $dbSettings['is_dev_mode'],
            null,
            $cache
        );*/

        $connection = DriverManager::getConnection($dbSettings['connection']);

        $connection->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

        $entityManager = new EntityManager($connection, $ORMConfig);

        return DependencyFactory::fromEntityManager(
            $migrationConfig,
            new ExistingEntityManager($entityManager),
            $logger
        );
    },

    EntityManagerInterface::class => function (ContainerInterface $c) {
        /** @var DependencyFactory $dependencyFactory */
        $dependencyFactory = $c->get(DependencyFactory::class);

        return $dependencyFactory->getEntityManager();
    },

    'config' => [
        'db' => [
            // Enables or disables Doctrine metadata caching
            // for either performance or convenience during development.
            'is_dev_mode' => $isDevMode,

            // Path where Doctrine will cache the processed metadata
            // when 'dev_mode' is false.
            'cache_dir' => ROOT_DIR . '/runtime/cache/doctrine/cache',
            'proxy_dir' => ROOT_DIR . '/runtime/cache/doctrine/proxy',

            // List of paths where Doctrine will search for metadata.
            // Metadata can be either YML/XML files or PHP classes annotated
            // with comments or PHP8 attributes.
            /*'metadata_dirs' => [ // for AttributeMetadataConfiguration
                ROOT_DIR . '/src/Modules/User/Domain/Model'
            ],*/

            'metadata_dirs' => [
                //'/path/to/files1' => 'PathToEntities\Entity',
                ROOT_DIR . '/src/Modules/User/Infrastructure/Db/Mapping' => 'PhpLab\Modules\User\Domain\Model'
            ],

            // The parameters Doctrine needs to connect to your database.
            // These parameters depend on the driver (for instance the 'pdo_sqlite' driver
            // needs a 'path' parameter and doesn't use most of the ones shown in this example).
            // Refer to the Doctrine documentation to see the full list
            // of valid parameters: https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html
            'connection' => [
                'driver' => $_ENV['DB_DRIVER'],
                'host' => $_ENV['DB_HOST'],
                'port' => (int) $_ENV['DB_PORT'],
                'dbname' => $_ENV['DB_DATABASE'],
                'user' => $_ENV['DB_USERNAME'],
                'password' => $_ENV['DB_PASSWORD'],
                'charset' => $_ENV['DB_CHARSET'],
            ],

            // doctrine/migrations config
            'migration' => [
                'table_storage' => [
                    'table_name' => 'migration',
                    'version_column_name' => 'version',
                    'version_column_length' => 191,
                    'executed_at_column_name' => 'executed_at',
                    'execution_time_column_name' => 'execution_time',
                ],

                'migrations_paths' => [
                    'CommonMigrations'  => ROOT_DIR . '/src/Infrastructure/Db/Migrations',
                    'UserMigrations'    => ROOT_DIR . '/src/Modules/User/Infrastructure/Db/Migrations',
                ],

                'all_or_nothing' => true,
                'transactional' => true,
                'check_database_platform' => true,
                'organize_migrations' => 'none',
                'connection' => null,
                'em' => null,
            ]
        ],
    ],

];