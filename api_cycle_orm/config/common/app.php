<?php
declare(strict_types=1);

use PhpLab\Application\Factory\App\HttpAppFactory;
use PhpLab\Application\Factory\Response\ResponseFactory;
use PhpLab\Application\Interface\Settings\SettingsInterface;
use PhpLab\Application\Middleware\ExceptionMiddleware;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\App;
use Symfony\Component\Translation\Translator;

return [
    App::class => function (ContainerInterface $container) {
        HttpAppFactory::setResponseFactory(new ResponseFactory());
        $app = HttpAppFactory::create();

        // Register middleware
        $middleware = require ROOT_DIR . '/config/middleware.php';
        $middleware($app);

        // Register routes
        $routes = require ROOT_DIR . '/config/routes.php';
        $routes($app);

        return $app;
    },

    ExceptionMiddleware::class => function (ContainerInterface $container) {
        /** @var SettingsInterface $settings */
        $settings = $container->get(SettingsInterface::class);
        /** @var App $app */
        $app = $container->get(App::class);
        $translator = $container->get(Translator::class);

        $displayErrorDetails = (bool) $settings->get('displayErrorDetails');

        return new ExceptionMiddleware(
            $translator,
            $app->getResponseFactory(),
            $container->get(LoggerInterface::class),
            $displayErrorDetails
        );
    }
];