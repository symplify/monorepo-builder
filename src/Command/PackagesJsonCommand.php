<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Command;

use MonorepoBuilder20211223\Nette\Utils\Json;
use MonorepoBuilder20211223\Symfony\Component\Console\Input\InputInterface;
use MonorepoBuilder20211223\Symfony\Component\Console\Input\InputOption;
use MonorepoBuilder20211223\Symfony\Component\Console\Output\OutputInterface;
use Symplify\MonorepoBuilder\Json\PackageJsonProvider;
use Symplify\MonorepoBuilder\ValueObject\Option;
use MonorepoBuilder20211223\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use MonorepoBuilder20211223\Symplify\PackageBuilder\Console\Command\CommandNaming;
final class PackagesJsonCommand extends \MonorepoBuilder20211223\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand
{
    /**
     * @var \Symplify\MonorepoBuilder\Json\PackageJsonProvider
     */
    private $packageJsonProvider;
    public function __construct(\Symplify\MonorepoBuilder\Json\PackageJsonProvider $packageJsonProvider)
    {
        $this->packageJsonProvider = $packageJsonProvider;
        parent::__construct();
    }
    protected function configure() : void
    {
        $this->setName(\MonorepoBuilder20211223\Symplify\PackageBuilder\Console\Command\CommandNaming::classToName(self::class));
        $this->setDescription('Provides package paths in json format. Useful for GitHub Actions Workflow');
        $this->addOption(\Symplify\MonorepoBuilder\ValueObject\Option::TESTS, null, \MonorepoBuilder20211223\Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Only with /tests directory');
        $this->addOption(\Symplify\MonorepoBuilder\ValueObject\Option::EXCLUDE_PACKAGE, null, \MonorepoBuilder20211223\Symfony\Component\Console\Input\InputOption::VALUE_IS_ARRAY | \MonorepoBuilder20211223\Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED, 'Exclude one or more package from the list, useful e.g. when scoping one package instead of bare split');
    }
    protected function execute(\MonorepoBuilder20211223\Symfony\Component\Console\Input\InputInterface $input, \MonorepoBuilder20211223\Symfony\Component\Console\Output\OutputInterface $output) : int
    {
        $onlyTests = (bool) $input->getOption(\Symplify\MonorepoBuilder\ValueObject\Option::TESTS);
        if ($onlyTests) {
            $packagePaths = $this->packageJsonProvider->providePackagesWithTests();
        } else {
            $packagePaths = $this->packageJsonProvider->providePackages();
        }
        $excludedPackages = (array) $input->getOption(\Symplify\MonorepoBuilder\ValueObject\Option::EXCLUDE_PACKAGE);
        $allowedPackagePaths = \array_diff($packagePaths, $excludedPackages);
        // re-index from 0
        $allowedPackagePaths = \array_values($allowedPackagePaths);
        // must be without spaces, otherwise it breaks GitHub Actions json
        $json = \MonorepoBuilder20211223\Nette\Utils\Json::encode($allowedPackagePaths);
        echo $json;
        return self::SUCCESS;
    }
}
