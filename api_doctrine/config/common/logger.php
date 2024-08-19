<?php
declare(strict_types=1);

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use PhpLab\Application\Interface\Settings\SettingsInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return [
    LoggerInterface::class => function (ContainerInterface $c) {
        $settings = $c->get(SettingsInterface::class);

        $loggerSettings = $settings->get('logger');
        $logger = new Logger($loggerSettings['name']);

        $processor = new UidProcessor();
        $logger->pushProcessor($processor);

        $rotatingFileHandler = new RotatingFileHandler(
            $loggerSettings['path'],
            0,
            $loggerSettings['level'],
            true,
            0777
        );
        $rotatingFileHandler->setFormatter(new LineFormatter(
            null,
            null,
            false,
            true
        ));

        // $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
        // $logger->pushHandler($handler);
        $logger->pushHandler($rotatingFileHandler);

        return $logger;
    },

    'config' => [
        'logger' => [
            'name'  => 'app-logger',
            'path'  => isset($_ENV['docker']) ? 'php://stdout' : ROOT_DIR . '/runtime/logs/app.log',
            'level' => $_ENV['IS_DEV_MODE'] ? Level::Debug : Level::Error
        ],
    ],
];