<?php

declare (strict_types=1);
namespace MonorepoBuilder20211223\Symplify\PackageBuilder\Console\Style;

use MonorepoBuilder20211223\Symfony\Component\Console\Application;
use MonorepoBuilder20211223\Symfony\Component\Console\Input\ArgvInput;
use MonorepoBuilder20211223\Symfony\Component\Console\Output\ConsoleOutput;
use MonorepoBuilder20211223\Symfony\Component\Console\Output\OutputInterface;
use MonorepoBuilder20211223\Symfony\Component\Console\Style\SymfonyStyle;
use MonorepoBuilder20211223\Symplify\EasyTesting\PHPUnit\StaticPHPUnitEnvironment;
use MonorepoBuilder20211223\Symplify\PackageBuilder\Reflection\PrivatesCaller;
/**
 * @api
 */
final class SymfonyStyleFactory
{
    /**
     * @var \Symplify\PackageBuilder\Reflection\PrivatesCaller
     */
    private $privatesCaller;
    public function __construct()
    {
        $this->privatesCaller = new \MonorepoBuilder20211223\Symplify\PackageBuilder\Reflection\PrivatesCaller();
    }
    public function create() : \MonorepoBuilder20211223\Symfony\Component\Console\Style\SymfonyStyle
    {
        // to prevent missing argv indexes
        if (!isset($_SERVER['argv'])) {
            $_SERVER['argv'] = [];
        }
        $argvInput = new \MonorepoBuilder20211223\Symfony\Component\Console\Input\ArgvInput();
        $consoleOutput = new \MonorepoBuilder20211223\Symfony\Component\Console\Output\ConsoleOutput();
        // to configure all -v, -vv, -vvv options without memory-lock to Application run() arguments
        $this->privatesCaller->callPrivateMethod(new \MonorepoBuilder20211223\Symfony\Component\Console\Application(), 'configureIO', [$argvInput, $consoleOutput]);
        // --debug is called
        if ($argvInput->hasParameterOption('--debug')) {
            $consoleOutput->setVerbosity(\MonorepoBuilder20211223\Symfony\Component\Console\Output\OutputInterface::VERBOSITY_DEBUG);
        }
        // disable output for tests
        if (\MonorepoBuilder20211223\Symplify\EasyTesting\PHPUnit\StaticPHPUnitEnvironment::isPHPUnitRun()) {
            $consoleOutput->setVerbosity(\MonorepoBuilder20211223\Symfony\Component\Console\Output\OutputInterface::VERBOSITY_QUIET);
        }
        return new \MonorepoBuilder20211223\Symfony\Component\Console\Style\SymfonyStyle($argvInput, $consoleOutput);
    }
}
