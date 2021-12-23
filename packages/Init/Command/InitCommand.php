<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Init\Command;

use MonorepoBuilder20211223\Symfony\Component\Console\Input\InputArgument;
use MonorepoBuilder20211223\Symfony\Component\Console\Input\InputInterface;
use MonorepoBuilder20211223\Symfony\Component\Console\Output\OutputInterface;
use MonorepoBuilder20211223\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use MonorepoBuilder20211223\Symplify\PackageBuilder\Console\Command\CommandNaming;
final class InitCommand extends \MonorepoBuilder20211223\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand
{
    /**
     * @var string
     */
    private const OUTPUT = 'output';
    protected function configure() : void
    {
        $this->setName(\MonorepoBuilder20211223\Symplify\PackageBuilder\Console\Command\CommandNaming::classToName(self::class));
        $this->setDescription('Creates empty monorepo directory and composer.json structure.');
        $this->addArgument(self::OUTPUT, \MonorepoBuilder20211223\Symfony\Component\Console\Input\InputArgument::OPTIONAL, 'Directory to generate monorepo into.', \getcwd());
    }
    protected function execute(\MonorepoBuilder20211223\Symfony\Component\Console\Input\InputInterface $input, \MonorepoBuilder20211223\Symfony\Component\Console\Output\OutputInterface $output) : int
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
