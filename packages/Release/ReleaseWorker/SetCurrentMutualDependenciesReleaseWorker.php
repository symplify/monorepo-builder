<?php

declare (strict_types=1);
namespace MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use MonorepoBuilder20210705\PharIo\Version\Version;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\DependencyUpdater;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Package\PackageNamesProvider;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Utils\VersionUtils;
final class SetCurrentMutualDependenciesReleaseWorker implements \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface
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
    public function __construct(\MonorepoBuilder20210705\Symplify\MonorepoBuilder\Utils\VersionUtils $versionUtils, \MonorepoBuilder20210705\Symplify\MonorepoBuilder\DependencyUpdater $dependencyUpdater, \MonorepoBuilder20210705\Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider $composerJsonProvider, \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Package\PackageNamesProvider $packageNamesProvider)
    {
        $this->versionUtils = $versionUtils;
        $this->dependencyUpdater = $dependencyUpdater;
        $this->composerJsonProvider = $composerJsonProvider;
        $this->packageNamesProvider = $packageNamesProvider;
    }
    public function work(\MonorepoBuilder20210705\PharIo\Version\Version $version) : void
    {
        $versionInString = $this->versionUtils->getRequiredFormat($version);
        $this->dependencyUpdater->updateFileInfosWithPackagesAndVersion($this->composerJsonProvider->getPackagesComposerFileInfos(), $this->packageNamesProvider->provide(), $versionInString);
        // give time to propagate values before commit
        \sleep(1);
    }
    public function getDescription(\MonorepoBuilder20210705\PharIo\Version\Version $version) : string
    {
        $versionInString = $this->versionUtils->getRequiredFormat($version);
        return \sprintf('Set packages mutual dependencies to "%s" version', $versionInString);
    }
}
