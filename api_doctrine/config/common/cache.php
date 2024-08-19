<?php
declare(strict_types=1);

use PhpLab\Application\Interface\Settings\SettingsInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\CacheInterface;

return [
    CacheInterface::class => DI\get(FilesystemAdapter::class),

    FilesystemAdapter::class => function (ContainerInterface $c) {
        $settings = $c->get(SettingsInterface::class);

        $cacheSettings = $settings->get('cache');

        return new FilesystemAdapter(
            '',
            $cacheSettings['defaultLifetime'],
            $cacheSettings['directory']
        );
    },

    'config' => [
        'cache' => [
            'defaultLifetime'  => 60,
            'directory'  => ROOT_DIR . '/runtime/cache',
        ],
    ],
];