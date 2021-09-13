<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Command;

use MonorepoBuilder20210913\Nette\Utils\Json;
use MonorepoBuilder20210913\Symfony\Component\Console\Input\InputInterface;
use MonorepoBuilder20210913\Symfony\Component\Console\Input\InputOption;
use MonorepoBuilder20210913\Symfony\Component\Console\Output\OutputInterface;
use Symplify\MonorepoBuilder\Json\PackageJsonProvider;
use Symplify\MonorepoBuilder\ValueObject\Option;
use MonorepoBuilder20210913\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
final class PackagesJsonCommand extends \MonorepoBuilder20210913\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand
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
        $this->setDescription('Provides package paths in json format. Useful for GitHub Actions Workflow');
        $this->addOption(\Symplify\MonorepoBuilder\ValueObject\Option::TESTS, null, \MonorepoBuilder20210913\Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Only with /tests directory');
        $this->addOption(\Symplify\MonorepoBuilder\ValueObject\Option::EXCLUDE_PACKAGE, null, \MonorepoBuilder20210913\Symfony\Component\Console\Input\InputOption::VALUE_IS_ARRAY | \MonorepoBuilder20210913\Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED, 'Exclude one or more package from the list, useful e.g. when scoping one package instead of bare split');
    }
    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute($input, $output) : int
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
        $json = \MonorepoBuilder20210913\Nette\Utils\Json::encode($allowedPackagePaths);
        echo $json;
        return self::SUCCESS;
    }
}
