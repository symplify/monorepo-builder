<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Testing;

use MonorepoBuilder202209\Symfony\Component\Console\Style\SymfonyStyle;
use MonorepoBuilder202209\Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager;
use MonorepoBuilder202209\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Package\PackageNamesProvider;
use Symplify\MonorepoBuilder\Testing\ComposerJson\ComposerJsonSymlinker;
use Symplify\MonorepoBuilder\Testing\PackageDependency\UsedPackagesResolver;
use MonorepoBuilder202209\Symplify\PackageBuilder\Console\Output\ConsoleDiffer;
use MonorepoBuilder202209\Symplify\SmartFileSystem\SmartFileInfo;
use MonorepoBuilder202209\Symplify\SymplifyKernel\Exception\ShouldNotHappenException;
final class ComposerJsonRepositoriesUpdater
{
    /**
     * @var \Symplify\MonorepoBuilder\Package\PackageNamesProvider
     */
    private $packageNamesProvider;
    /**
     * @var \Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager
     */
    private $jsonFileManager;
    /**
     * @var \Symfony\Component\Console\Style\SymfonyStyle
     */
    private $symfonyStyle;
    /**
     * @var \Symplify\MonorepoBuilder\Testing\ComposerJson\ComposerJsonSymlinker
     */
    private $composerJsonSymlinker;
    /**
     * @var \Symplify\MonorepoBuilder\Testing\PackageDependency\UsedPackagesResolver
     */
    private $usedPackagesResolver;
    /**
     * @var \Symplify\PackageBuilder\Console\Output\ConsoleDiffer
     */
    private $consoleDiffer;
    public function __construct(PackageNamesProvider $packageNamesProvider, JsonFileManager $jsonFileManager, SymfonyStyle $symfonyStyle, ComposerJsonSymlinker $composerJsonSymlinker, UsedPackagesResolver $usedPackagesResolver, ConsoleDiffer $consoleDiffer)
    {
        $this->packageNamesProvider = $packageNamesProvider;
        $this->jsonFileManager = $jsonFileManager;
        $this->symfonyStyle = $symfonyStyle;
        $this->composerJsonSymlinker = $composerJsonSymlinker;
        $this->usedPackagesResolver = $usedPackagesResolver;
        $this->consoleDiffer = $consoleDiffer;
    }
    public function processPackage(SmartFileInfo $packageFileInfo, ComposerJson $rootComposerJson, bool $symlink) : void
    {
        $packageComposerJson = $this->jsonFileManager->loadFromFileInfo($packageFileInfo);
        $usedPackageNames = $this->usedPackagesResolver->resolveForPackage($packageComposerJson);
        if ($usedPackageNames === []) {
            $message = \sprintf('Package "%s" does not use any mutual dependencies, so we skip it', $packageFileInfo->getRelativeFilePathFromCwd());
            $this->symfonyStyle->note($message);
            return;
        }
        // possibly replace them all to cover recursive secondary dependencies
        $packageNames = $this->packageNamesProvider->provide();
        $oldComposerJsonContents = $packageFileInfo->getContents();
        $rootComposerJsonFileInfo = $rootComposerJson->getFileInfo();
        if (!$rootComposerJsonFileInfo instanceof SmartFileInfo) {
            throw new ShouldNotHappenException();
        }
        $decoreatedPackageComposerJson = $this->composerJsonSymlinker->decoratePackageComposerJsonWithPackageSymlinks($packageFileInfo, $packageNames, $rootComposerJsonFileInfo, $symlink);
        $newComposerJsonContents = $this->jsonFileManager->printJsonToFileInfoAndReturn($decoreatedPackageComposerJson, $packageFileInfo);
        $message = \sprintf('File "%s" was updated', $packageFileInfo->getRelativeFilePathFromCwd());
        $this->symfonyStyle->title($message);
        $diff = $this->consoleDiffer->diff($oldComposerJsonContents, $newComposerJsonContents);
        $this->symfonyStyle->writeln($diff);
        $this->symfonyStyle->newLine(2);
    }
}
