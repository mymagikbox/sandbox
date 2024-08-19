<?php
declare(strict_types=1);

use DI\ContainerBuilder;

use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;
use PhpLab\Application\Settings\Settings;
use PhpLab\Application\Settings\SettingsInterface;

return function (ContainerBuilder $containerBuilder) {

    $aggregator = new ConfigAggregator([
        new PhpFileProvider(__DIR__ . '/common/*.php'),
    ]);

    $collection = $aggregator->getMergedConfig();

    $config = $collection['config'] ?? [];
    unset($collection['config']);

    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () use ($config) {
            return new Settings($config);
        }
    ]);

    $containerBuilder->addDefinitions($collection);
};
