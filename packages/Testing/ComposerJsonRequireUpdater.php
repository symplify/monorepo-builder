<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Testing;

use MonorepoBuilder202209\Symfony\Component\Console\Style\SymfonyStyle;
use MonorepoBuilder202209\Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager;
use Symplify\MonorepoBuilder\Testing\ComposerJson\ComposerVersionManipulator;
use Symplify\MonorepoBuilder\Testing\PackageDependency\UsedPackagesResolver;
use MonorepoBuilder202209\Symplify\PackageBuilder\Console\Output\ConsoleDiffer;
use MonorepoBuilder202209\Symplify\SmartFileSystem\SmartFileInfo;
final class ComposerJsonRequireUpdater
{
    /**
     * @var \Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager
     */
    private $jsonFileManager;
    /**
     * @var \Symfony\Component\Console\Style\SymfonyStyle
     */
    private $symfonyStyle;
    /**
     * @var \Symplify\MonorepoBuilder\Testing\ComposerJson\ComposerVersionManipulator
     */
    private $composerVersionManipulator;
    /**
     * @var \Symplify\MonorepoBuilder\Testing\PackageDependency\UsedPackagesResolver
     */
    private $usedPackagesResolver;
    /**
     * @var \Symplify\PackageBuilder\Console\Output\ConsoleDiffer
     */
    private $consoleDiffer;
    public function __construct(JsonFileManager $jsonFileManager, SymfonyStyle $symfonyStyle, ComposerVersionManipulator $composerVersionManipulator, UsedPackagesResolver $usedPackagesResolver, ConsoleDiffer $consoleDiffer)
    {
        $this->jsonFileManager = $jsonFileManager;
        $this->symfonyStyle = $symfonyStyle;
        $this->composerVersionManipulator = $composerVersionManipulator;
        $this->usedPackagesResolver = $usedPackagesResolver;
        $this->consoleDiffer = $consoleDiffer;
    }
    public function processPackage(SmartFileInfo $packageFileInfo) : void
    {
        $packageComposerJson = $this->jsonFileManager->loadFromFileInfo($packageFileInfo);
        $usedPackageNames = $this->usedPackagesResolver->resolveForPackage($packageComposerJson);
        if ($usedPackageNames === []) {
            $message = \sprintf('Package "%s" does not use any mutual dependencies, so we skip it', $packageFileInfo->getRelativeFilePathFromCwd());
            $this->symfonyStyle->note($message);
            return;
        }
        $packageComposerJson = $this->composerVersionManipulator->decorateAsteriskVersionForUsedPackages($packageComposerJson, $usedPackageNames);
        $oldComposerJsonContents = $packageFileInfo->getContents();
        $newComposerJsonContents = $this->jsonFileManager->printJsonToFileInfoAndReturn($packageComposerJson, $packageFileInfo);
        $message = \sprintf('File "%s" was updated', $packageFileInfo->getRelativeFilePathFromCwd());
        $this->symfonyStyle->title($message);
        $diff = $this->consoleDiffer->diff($oldComposerJsonContents, $newComposerJsonContents);
        $this->symfonyStyle->writeln($diff);
        $this->symfonyStyle->newLine(2);
    }
}
