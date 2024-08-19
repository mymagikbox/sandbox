<?php
declare(strict_types=1);

namespace PhpLab\Application;

use PhpLab\Application\Handlers\HttpErrorHandler;
use PhpLab\Application\Handlers\ShutdownHandler;
use PhpLab\Application\ResponseEmitter\ResponseEmitter;
use PhpLab\Application\Settings\SettingsInterface;
use DI\Container;
use DI\ContainerBuilder;
use Psr\Log\LoggerInterface;
use Slim\Factory\ServerRequestCreatorFactory;
use Phinx\Console\PhinxApplication;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Translation\Translator;

final class AppFactory extends \Slim\Factory\AppFactory
{
    public static string $ROOT_DIR = __DIR__ . '/../../';

    public static function getContainer(): Container
    {
        return self::$container;
    }
    public static function buildContainer(): Container
    {
        require __DIR__ . '/DotEnv.php';

        // Instantiate PHP-DI ContainerBuilder
        $containerBuilder = new ContainerBuilder();

        if (!(bool) $_ENV['IS_DEV_MODE']) { // Should be set to true in production
            $containerBuilder->enableCompilation(self::$ROOT_DIR . '/var/cache');
        }

        // Set up dependencies
        $dependencies = require self::$ROOT_DIR . 'config/dependencies.php';
        $dependencies($containerBuilder);

        // Build PHP-DI Container instance
        $container = $containerBuilder->build();
        $db = $container->get(DB::class);

        self::setContainer($container);

        return $container;
    }

    public static function prepareAndRun(): void
    {
        $container = self::buildContainer();

        // Instantiate the app
        $app = AppFactory::create();
        $callableResolver = $app->getCallableResolver();

        // Register middleware
        $middleware = require self::$ROOT_DIR . 'config/middleware.php';
        $middleware($app);

        // Register routes
        $routes = require self::$ROOT_DIR . 'config/routes.php';
        $routes($app);

        /** @var SettingsInterface $settings */
        $settings = $container->get(SettingsInterface::class);

        //set timezone
        $tz = $settings->get('timezone');
        date_default_timezone_set($tz);

        $displayErrorDetails = $settings->get('displayErrorDetails');
        $logError = $settings->get('logError');
        $logErrorDetails = $settings->get('logErrorDetails');

        // Create Request object from globals
        $serverRequestCreator = ServerRequestCreatorFactory::create();
        $request = $serverRequestCreator->createServerRequestFromGlobals();

        // Create Error Handler
        $responseFactory = $app->getResponseFactory();
        $errorHandler = new HttpErrorHandler(
            $container->get(Translator::class),
            $callableResolver,
            $responseFactory,
            $container->get(LoggerInterface::class)
        );

        // Create Shutdown Handler
        $shutdownHandler = new ShutdownHandler($request, $errorHandler, $displayErrorDetails);
        register_shutdown_function($shutdownHandler);

        // Add Routing Middleware
        $app->addRoutingMiddleware();

        // Add Body Parsing Middleware
        $app->addBodyParsingMiddleware();

        // Add Error Middleware
        $errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, $logError, $logErrorDetails);
        $errorMiddleware->setDefaultErrorHandler($errorHandler);

        // Run App & Emit Response
        $response = $app->handle($request);
        $responseEmitter = new ResponseEmitter();
        $responseEmitter->emit($response);
    }

    public static function prepareAndRunConsoleApp(): ConsoleApplication
    {
        $container = self::buildContainer();

        $consoleApp = new ConsoleApplication();

        // register console commands
        $commandFn = require self::$ROOT_DIR . 'config/console.commands.php';
        $commandFn($consoleApp, $container);

        return $consoleApp;
    }

    public static function prepareAndRunPhinxApp(): PhinxApplication
    {
        $container = self::buildContainer(); // need to create DB conection

        return new PhinxApplication();
    }
}