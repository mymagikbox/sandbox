<?php
declare(strict_types=1);

namespace PhpLab\Application\Console;

use OpenApi\Generator;
use PhpLab\Application\AppFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ApiDocsGeneratorCommand extends BaseCommand
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'open-api:generate-docs';

    /*** @var $output OutputInterface */
    public $output;

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Generate api docs command.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows generate api docs')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $srcDir = AppFactory::$ROOT_DIR . 'src';
        $apiFileConfig = AppFactory::$ROOT_DIR . 'public/docs/schema/openapi.yaml';

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