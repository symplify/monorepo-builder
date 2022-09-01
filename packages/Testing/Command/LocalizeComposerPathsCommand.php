<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Testing\Command;

use MonorepoBuilder202209\Symfony\Component\Console\Input\InputArgument;
use MonorepoBuilder202209\Symfony\Component\Console\Input\InputInterface;
use MonorepoBuilder202209\Symfony\Component\Console\Output\OutputInterface;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use Symplify\MonorepoBuilder\Testing\ComposerJsonRepositoriesUpdater;
use Symplify\MonorepoBuilder\Testing\ComposerJsonRequireUpdater;
use Symplify\MonorepoBuilder\Testing\ValueObject\Option;
use MonorepoBuilder202209\Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use MonorepoBuilder202209\Symplify\SmartFileSystem\SmartFileInfo;
final class LocalizeComposerPathsCommand extends AbstractSymplifyCommand
{
    /**
     * @var \Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider
     */
    private $composerJsonProvider;
    /**
     * @var \Symplify\MonorepoBuilder\Testing\ComposerJsonRequireUpdater
     */
    private $composerJsonRequireUpdater;
    /**
     * @var \Symplify\MonorepoBuilder\Testing\ComposerJsonRepositoriesUpdater
     */
    private $composerJsonRepositoriesUpdater;
    public function __construct(ComposerJsonProvider $composerJsonProvider, ComposerJsonRequireUpdater $composerJsonRequireUpdater, ComposerJsonRepositoriesUpdater $composerJsonRepositoriesUpdater)
    {
        $this->composerJsonProvider = $composerJsonProvider;
        $this->composerJsonRequireUpdater = $composerJsonRequireUpdater;
        $this->composerJsonRepositoriesUpdater = $composerJsonRepositoriesUpdater;
        parent::__construct();
    }
    protected function configure() : void
    {
        $this->setName('localize-composer-paths');
        $this->setDescription('Set mutual package paths to local packages - use for pre-split package testing');
        $this->addArgument(Option::PACKAGE_COMPOSER_JSON, InputArgument::REQUIRED, 'Path to package "composer.json"');
    }
    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $packageComposerJson = (string) $input->getArgument(Option::PACKAGE_COMPOSER_JSON);
        $this->fileSystemGuard->ensureFileExists($packageComposerJson, __METHOD__);
        $packageComposerJsonFileInfo = new SmartFileInfo($packageComposerJson);
        $rootComposerJson = $this->composerJsonProvider->getRootComposerJson();
        // 1. update "require" to "*" for all local packages
        $packagesFileInfos = $this->composerJsonProvider->getPackagesComposerFileInfos();
        foreach ($packagesFileInfos as $packageFileInfo) {
            $this->composerJsonRequireUpdater->processPackage($packageFileInfo);
        }
        // 2. update "repository" to "*" for current composer.json
        $this->composerJsonRepositoriesUpdater->processPackage($packageComposerJsonFileInfo, $rootComposerJson, \false);
        $message = \sprintf('Package paths in "%s" have been updated', $packageComposerJsonFileInfo->getRelativeFilePathFromCwd());
        $this->symfonyStyle->success($message);
        return self::SUCCESS;
    }
}
