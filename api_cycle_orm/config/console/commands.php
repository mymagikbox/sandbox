<?php
declare(strict_types=1);

use DI\Container;
use PhpLab\Application\Console\TestCommand;
use PhpLab\Application\Console\ApiDocsGeneratorCommand;
use Symfony\Component\Console\Application as ConsoleApplication;

return function (ConsoleApplication $app, Container $container) {
    // ... register commands
    $app->add(new TestCommand($container));
    $app->add(new ApiDocsGeneratorCommand($container));
};
