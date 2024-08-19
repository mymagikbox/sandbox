<?php
declare(strict_types=1);

namespace PhpLab\Application\Factory\App;

use Doctrine\Migrations\DependencyFactory;
use Symfony\Component\Console\Application as ConsoleApplication;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;

use Doctrine\Migrations\Tools\Console\ConsoleRunner as MigrationConsoleRunner;

final class DoctrineAppFactory extends BaseAppFactory
{
    public static function prepare(): ConsoleApplication
    {
        self::buildContainer();

        $em = self::$container->get(EntityManagerInterface::class);

        /** @var DependencyFactory $dependencyFactory */
        $dependencyFactory = self::$container->get(DependencyFactory::class);

        $cli = MigrationConsoleRunner::createApplication([], $dependencyFactory);

        ConsoleRunner::addCommands($cli, new SingleManagerProvider($em));

        return $cli;
    }
}