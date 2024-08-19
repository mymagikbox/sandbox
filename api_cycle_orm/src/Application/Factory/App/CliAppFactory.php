<?php
declare(strict_types=1);

namespace PhpLab\Application\Factory\App;

use Symfony\Component\Console\Application as ConsoleApplication;

final class CliAppFactory extends BaseAppFactory
{
    public static function prepare(): ConsoleApplication
    {
        self::buildContainer();

        $consoleApp = new ConsoleApplication();

        // register console commands
        $commandFn = require ROOT_DIR . '/config/console/commands.php';
        $commandFn($consoleApp, self::$container);

        return $consoleApp;
    }
}