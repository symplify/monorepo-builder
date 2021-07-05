<?php

declare (strict_types=1);
namespace MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use MonorepoBuilder20210705\PharIo\Version\Version;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\DevMasterAliasUpdater;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Utils\VersionUtils;
final class UpdateBranchAliasReleaseWorker implements \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface
{
    /**
     * @var \Symplify\MonorepoBuilder\DevMasterAliasUpdater
     */
    private $devMasterAliasUpdater;
    /**
     * @var \Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider
     */
    private $composerJsonProvider;
    /**
     * @var \Symplify\MonorepoBuilder\Utils\VersionUtils
     */
    private $versionUtils;
    public function __construct(\MonorepoBuilder20210705\Symplify\MonorepoBuilder\DevMasterAliasUpdater $devMasterAliasUpdater, \MonorepoBuilder20210705\Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider $composerJsonProvider, \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Utils\VersionUtils $versionUtils)
    {
        $this->devMasterAliasUpdater = $devMasterAliasUpdater;
        $this->composerJsonProvider = $composerJsonProvider;
        $this->versionUtils = $versionUtils;
    }
    public function work(\MonorepoBuilder20210705\PharIo\Version\Version $version) : void
    {
        $nextAlias = $this->versionUtils->getNextAliasFormat($version);
        $this->devMasterAliasUpdater->updateFileInfosWithAlias($this->composerJsonProvider->getPackagesComposerFileInfos(), $nextAlias);
    }
    public function getDescription(\MonorepoBuilder20210705\PharIo\Version\Version $version) : string
    {
        $nextAlias = $this->versionUtils->getNextAliasFormat($version);
        return \sprintf('Set branch alias "%s" to all packages', $nextAlias);
    }
}
