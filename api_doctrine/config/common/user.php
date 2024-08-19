<?php
declare(strict_types=1);

use PhpLab\Application\Interface\Settings\SettingsInterface;
use PhpLab\Modules\User\Domain\Repository\RefreshTokenRepositoryInterface;
use PhpLab\Modules\User\Domain\Repository\UserRepositoryInterface;
use PhpLab\Modules\User\Domain\Service\AccessTokenService;
use PhpLab\Modules\User\Infrastructure\Repository\Mysql\RefreshTokenRepository;
use PhpLab\Modules\User\Infrastructure\Repository\Mysql\UserRepository;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

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
    RefreshTokenRepositoryInterface::class => \DI\autowire(RefreshTokenRepository::class),

    'config' => [
        'token' => [
            'accessSecretKey' => $_ENV['ACCESS_TOKEN_SECRET'],
            'refreshSecretKey' => $_ENV['REFRESH_TOKEN_SECRET'],
            'algorithm' => 'HS256'
        ],
    ],
];