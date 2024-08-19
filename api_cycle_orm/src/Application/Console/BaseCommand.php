<?php
declare(strict_types=1);

namespace PhpLab\Application\Console;

use DI\Container;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
class BaseCommand extends Command
{
    protected Container $container;
    protected LoggerInterface $logger;

    public function __construct(Container $container, string $name = null)
    {
        $this->container = $container;
        $this->logger = $container->get(LoggerInterface::class);

        parent::__construct($name);
    }
}