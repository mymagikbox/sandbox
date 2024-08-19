<?php
declare(strict_types=1);

namespace PhpLab\Application\Console;

use OpenApi\Generator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'open-api:generate-docs',
    description: 'Generate api docs command.',
    hidden: false
)]
final class ApiDocsGeneratorCommand extends BaseCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $srcDir = ROOT_DIR . '/src';
        $apiFileConfig = ROOT_DIR . '/public/docs/schema/openapi.yaml';

        $openapi = Generator::scan([$srcDir]);

        $output->writeln($openapi->toYaml());

        if (file_exists($apiFileConfig)) {
            if (is_writable(dirname($apiFileConfig)) && is_writable($apiFileConfig)) {
                @unlink($apiFileConfig);
            }
        }

        file_put_contents($apiFileConfig, $openapi->toYaml());

        $output->writeln([
            "================",
            "Generate api docs completed",
            "================",
        ]);

        return Command::SUCCESS; // or Command::FAILURE;
    }
}