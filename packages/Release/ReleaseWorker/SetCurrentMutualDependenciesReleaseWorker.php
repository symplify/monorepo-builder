<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;
use Symplify\MonorepoBuilder\DependencyUpdater;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use Symplify\MonorepoBuilder\Package\PackageNamesProvider;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\MonorepoBuilder\Utils\VersionUtils;
final class SetCurrentMutualDependenciesReleaseWorker implements \Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface
{
    /**
     * @var \Symplify\MonorepoBuilder\Utils\VersionUtils
     */
    private $versionUtils;
    /**
     * @var \Symplify\MonorepoBuilder\DependencyUpdater
     */
    private $dependencyUpdater;
    /**
     * @var \Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider
     */
    private $composerJsonProvider;
    /**
     * @var \Symplify\MonorepoBuilder\Package\PackageNamesProvider
     */
    private $packageNamesProvider;
    public function __construct(\Symplify\MonorepoBuilder\Utils\VersionUtils $versionUtils, \Symplify\MonorepoBuilder\DependencyUpdater $dependencyUpdater, \Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider $composerJsonProvider, \Symplify\MonorepoBuilder\Package\PackageNamesProvider $packageNamesProvider)
    {
        $this->versionUtils = $versionUtils;
        $this->dependencyUpdater = $dependencyUpdater;
        $this->composerJsonProvider = $composerJsonProvider;
        $this->packageNamesProvider = $packageNamesProvider;
    }
    public function work(\PharIo\Version\Version $version) : void
    {
        $versionInString = $this->versionUtils->getRequiredFormat($version);
        $this->dependencyUpdater->updateFileInfosWithPackagesAndVersion($this->composerJsonProvider->getPackagesComposerFileInfos(), $this->packageNamesProvider->provide(), $versionInString);
        // give time to propagate values before commit
        \sleep(1);
    }
    public function getDescription(\PharIo\Version\Version $version) : string
    {
        $versionInString = $this->versionUtils->getRequiredFormat($version);
        return \sprintf('Set packages mutual dependencies to "%s" version', $versionInString);
    }
}
