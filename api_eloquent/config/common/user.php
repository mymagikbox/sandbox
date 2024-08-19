<?php
declare(strict_types=1);

use PhpLab\Application\Settings\SettingsInterface;
use Psr\Container\ContainerInterface;
use PhpLab\Modules\User\Domain\Services\AccessTokenService;
use Psr\Log\LoggerInterface;
use PhpLab\Modules\User\Domain\Repository\AccessTokenRepositoryInterface;
use PhpLab\Modules\User\Domain\Repository\UserRepositoryInterface;
use PhpLab\Modules\User\Infrastructure\Repository\UserRepository;
use PhpLab\Modules\User\Infrastructure\Repository\AccessTokenRepository;

return [
    AccessTokenService::class => function (ContainerInterface $c) {
        $settings = $c->get(SettingsInterface::class);
        $tokenSettings = $settings->get('token');

        return new AccessTokenService(
            $c->get(LoggerInterface::class),
            $tokenSettings['accessSecretKey'],
            $tokenSettings['refreshSecretKey'],
            $tokenSettings['algorithm']
        );
    },

    UserRepositoryInterface::class => \DI\autowire(UserRepository::class),
    AccessTokenRepositoryInterface::class => \DI\autowire(AccessTokenRepository::class),

    'config' => [
        'token' => [
            'accessSecretKey' => $_ENV['ACCESS_TOKEN_SECRET'],
            'refreshSecretKey' => $_ENV['REFRESH_TOKEN_SECRET'],
            'algorithm' => 'HS256'
        ],
    ],
];