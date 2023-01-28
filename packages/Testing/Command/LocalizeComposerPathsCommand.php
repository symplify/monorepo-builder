<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Testing\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use Symplify\MonorepoBuilder\Testing\ComposerJsonRepositoriesUpdater;
use Symplify\MonorepoBuilder\Testing\ComposerJsonRequireUpdater;
use Symplify\MonorepoBuilder\Testing\ValueObject\Option;
use Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use Symplify\SmartFileSystem\SmartFileInfo;
use Webmozart\Assert\Assert;

final class LocalizeComposerPathsCommand extends AbstractSymplifyCommand
{
    public function __construct(
        private ComposerJsonProvider $composerJsonProvider,
        private ComposerJsonRequireUpdater $composerJsonRequireUpdater,
        private ComposerJsonRepositoriesUpdater $composerJsonRepositoriesUpdater
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('localize-composer-paths');
        $this->setDescription('Set mutual package paths to local packages - use for pre-split package testing');
        $this->addArgument(Option::PACKAGE_COMPOSER_JSON, InputArgument::REQUIRED, 'Path to package "composer.json"');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $packageComposerJson = (string) $input->getArgument(Option::PACKAGE_COMPOSER_JSON);
        Assert::fileExists($packageComposerJson, __METHOD__);

        $packageComposerJsonFileInfo = new SmartFileInfo($packageComposerJson);
        $rootComposerJson = $this->composerJsonProvider->getRootComposerJson();

        // 1. update "require" to "*" for all local packages
        $packagesFileInfos = $this->composerJsonProvider->getPackagesComposerFileInfos();
        foreach ($packagesFileInfos as $packageFileInfo) {
            $this->composerJsonRequireUpdater->processPackage($packageFileInfo);
        }

        // 2. update "repository" to "*" for current composer.json
        $this->composerJsonRepositoriesUpdater->processPackage($packageComposerJsonFileInfo, $rootComposerJson, false);

        $message = sprintf(
            'Package paths in "%s" have been updated',
            $packageComposerJsonFileInfo->getRelativeFilePathFromCwd()
        );
        $this->symfonyStyle->success($message);

        return self::SUCCESS;
    }
}
