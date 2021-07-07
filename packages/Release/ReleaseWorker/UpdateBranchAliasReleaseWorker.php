<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Release\ReleaseWorker;

use MonorepoBuilder20210707\PharIo\Version\Version;
use Symplify\MonorepoBuilder\DevMasterAliasUpdater;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\MonorepoBuilder\Utils\VersionUtils;
final class UpdateBranchAliasReleaseWorker implements \Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface
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
    public function __construct(\Symplify\MonorepoBuilder\DevMasterAliasUpdater $devMasterAliasUpdater, \Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider $composerJsonProvider, \Symplify\MonorepoBuilder\Utils\VersionUtils $versionUtils)
    {
        $this->devMasterAliasUpdater = $devMasterAliasUpdater;
        $this->composerJsonProvider = $composerJsonProvider;
        $this->versionUtils = $versionUtils;
    }
    public function work(\MonorepoBuilder20210707\PharIo\Version\Version $version) : void
    {
        $nextAlias = $this->versionUtils->getNextAliasFormat($version);
        $this->devMasterAliasUpdater->updateFileInfosWithAlias($this->composerJsonProvider->getPackagesComposerFileInfos(), $nextAlias);
    }
    public function getDescription(\MonorepoBuilder20210707\PharIo\Version\Version $version) : string
    {
        $nextAlias = $this->versionUtils->getNextAliasFormat($version);
        return \sprintf('Set branch alias "%s" to all packages', $nextAlias);
    }
}
