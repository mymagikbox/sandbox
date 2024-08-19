<?php
declare(strict_types=1);

namespace PhpLab\Application\Console;

use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

#[AsCommand(
    name: 'app:test',
    description: 'Parse site command.',
    hidden: false
)]
final class TestCommand extends BaseCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->logger->info('Console Test Command');

        $output->writeln([
            "Test ended",
            "================",
        ]);

        return Command::SUCCESS; // or Command::FAILURE;
    }
}