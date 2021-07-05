<?php

declare (strict_types=1);
namespace MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use MonorepoBuilder20210705\PharIo\Version\Version;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\ConflictingUpdater;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Package\PackageNamesProvider;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Utils\VersionUtils;
final class SetCurrentMutualConflictsReleaseWorker implements \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface
{
    /**
     * @var \Symplify\MonorepoBuilder\Utils\VersionUtils
     */
    private $versionUtils;
    /**
     * @var \Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider
     */
    private $composerJsonProvider;
    /**
     * @var \Symplify\MonorepoBuilder\Package\PackageNamesProvider
     */
    private $packageNamesProvider;
    /**
     * @var \Symplify\MonorepoBuilder\ConflictingUpdater
     */
    private $conflictingUpdater;
    public function __construct(\MonorepoBuilder20210705\Symplify\MonorepoBuilder\Utils\VersionUtils $versionUtils, \MonorepoBuilder20210705\Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider $composerJsonProvider, \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Package\PackageNamesProvider $packageNamesProvider, \MonorepoBuilder20210705\Symplify\MonorepoBuilder\ConflictingUpdater $conflictingUpdater)
    {
        $this->versionUtils = $versionUtils;
        $this->composerJsonProvider = $composerJsonProvider;
        $this->packageNamesProvider = $packageNamesProvider;
        $this->conflictingUpdater = $conflictingUpdater;
    }
    public function work(\MonorepoBuilder20210705\PharIo\Version\Version $version) : void
    {
        $this->conflictingUpdater->updateFileInfosWithVendorAndVersion($this->composerJsonProvider->getPackagesComposerFileInfos(), $this->packageNamesProvider->provide(), $version);
        // give time to propagate printed composer.json values before commit
        \sleep(1);
    }
    public function getDescription(\MonorepoBuilder20210705\PharIo\Version\Version $version) : string
    {
        $versionInString = $this->versionUtils->getRequiredFormat($version);
        return \sprintf('Set packages mutual conflicts to "%s" version', $versionInString);
    }
}
