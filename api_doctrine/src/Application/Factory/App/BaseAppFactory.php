<?php
declare(strict_types=1);

namespace PhpLab\Application\Factory\App;

use DI\ContainerBuilder;
use PhpLab\Application\Interface\Settings\SettingsInterface;
use Psr\Container\ContainerInterface;
use Slim\Factory\AppFactory;

class BaseAppFactory extends AppFactory
{
    public static function getContainer(): ContainerInterface
    {
        return static::$container;
    }

    public static function buildContainer(): void
    {
        require ROOT_DIR . '/src/Application/DotEnv.php';

        // Instantiate PHP-DI ContainerBuilder
        $containerBuilder = new ContainerBuilder();

        if (!(bool) $_ENV['IS_DEV_MODE']) { // Should be set to true in production
            $containerBuilder->enableCompilation(ROOT_DIR . '/runtime/cache');
        }

        // Set up dependencies
        $dependencies = require ROOT_DIR . '/config/dependencies.php';
        $dependencies($containerBuilder);

        // Build PHP-DI Container instance
        $container = $containerBuilder->build();

        static::setContainer($container);

        // setup bootstrap params
        /** @var SettingsInterface $settings */
        $settings = $container->get(SettingsInterface::class);

        session_start();

        //set timezone
        $tz = $settings->get('timezone');
        date_default_timezone_set($tz);

        $mode = (bool) $_ENV['IS_DEV_MODE'];
        if($mode) {
            error_reporting(E_ALL ^ E_NOTICE);
            ini_set('display_errors', '1');
            ini_set('display_startup_errors', '1');
        }
    }
}