<?php
declare(strict_types=1);

namespace PhpLab\Application\Console;

use PhpLab\Application\AppFactory;
use Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

final class TestCommand extends BaseCommand
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:test';

    /*** @var $output OutputInterface */
    public $output;

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Parse site command.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to parse site')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $this->logger->info('sasdasd');

        $output->writeln([
            "Test ended",
            "================",
        ]);


        return Command::SUCCESS; // or Command::FAILURE;
    }
}