<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Init\Command;

use MonorepoBuilderPrefix202311\Symfony\Component\Console\Input\InputArgument;
use MonorepoBuilderPrefix202311\Symfony\Component\Console\Input\InputInterface;
use MonorepoBuilderPrefix202311\Symfony\Component\Console\Output\OutputInterface;
use MonorepoBuilderPrefix202311\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
final class InitCommand extends AbstractSymplifyCommand
{
    /**
     * @var string
     */
    private const OUTPUT = 'output';
    protected function configure() : void
    {
        $this->setName('init');
        $this->setDescription('Creates empty monorepo directory and composer.json structure.');
        $this->addArgument(self::OUTPUT, InputArgument::OPTIONAL, 'Directory to generate monorepo into.', \getcwd());
    }
    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        /** @var string $output */
        $output = $input->getArgument(self::OUTPUT);
        $this->smartFileSystem->mirror(__DIR__ . '/../../../templates/monorepo', $output);
        $this->symfonyStyle->success('Congrats! Your first monorepo is here.');
        $message = \sprintf('Try the next step - merge "composer.json" files from packages to the root one:%s "vendor/bin/monorepo-builder merge"', \PHP_EOL);
        $this->symfonyStyle->note($message);
        return self::SUCCESS;
    }
}
