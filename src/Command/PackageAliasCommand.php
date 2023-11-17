<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Command;

use MonorepoBuilderPrefix202311\Symfony\Component\Console\Input\InputInterface;
use MonorepoBuilderPrefix202311\Symfony\Component\Console\Output\OutputInterface;
use Symplify\MonorepoBuilder\DevMasterAliasUpdater;
use Symplify\MonorepoBuilder\Finder\PackageComposerFinder;
use Symplify\MonorepoBuilder\Git\ExpectedAliasResolver;
use MonorepoBuilderPrefix202311\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
final class PackageAliasCommand extends AbstractSymplifyCommand
{
    /**
     * @var \Symplify\MonorepoBuilder\Finder\PackageComposerFinder
     */
    private $packageComposerFinder;
    /**
     * @var \Symplify\MonorepoBuilder\DevMasterAliasUpdater
     */
    private $devMasterAliasUpdater;
    /**
     * @var \Symplify\MonorepoBuilder\Git\ExpectedAliasResolver
     */
    private $expectedAliasResolver;
    public function __construct(PackageComposerFinder $packageComposerFinder, DevMasterAliasUpdater $devMasterAliasUpdater, ExpectedAliasResolver $expectedAliasResolver)
    {
        $this->packageComposerFinder = $packageComposerFinder;
        $this->devMasterAliasUpdater = $devMasterAliasUpdater;
        $this->expectedAliasResolver = $expectedAliasResolver;
        parent::__construct();
    }
    protected function configure() : void
    {
        $this->setName('package-alias');
        $this->setDescription('Updates branch alias in "composer.json" all found packages');
    }
    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $composerPackageFiles = $this->packageComposerFinder->getPackageComposerFiles();
        if ($composerPackageFiles === []) {
            $this->symfonyStyle->error('No "composer.json" were found in packages.');
            return self::FAILURE;
        }
        $expectedAlias = $this->expectedAliasResolver->resolve();
        $this->devMasterAliasUpdater->updateFileInfosWithAlias($composerPackageFiles, $expectedAlias);
        $message = \sprintf('Alias was updated to "%s" in all packages.', $expectedAlias);
        $this->symfonyStyle->success($message);
        return self::SUCCESS;
    }
}
