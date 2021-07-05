<?php

declare (strict_types=1);
namespace MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use MonorepoBuilder20210705\PharIo\Version\Version;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Process\ProcessRunner;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Utils\VersionUtils;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\ValueObject\Option;
use MonorepoBuilder20210705\Symplify\PackageBuilder\Parameter\ParameterProvider;
final class PushNextDevReleaseWorker implements \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface
{
    /**
     * @var string
     */
    private $branchName;
    /**
     * @var \Symplify\MonorepoBuilder\Release\Process\ProcessRunner
     */
    private $processRunner;
    /**
     * @var \Symplify\MonorepoBuilder\Utils\VersionUtils
     */
    private $versionUtils;
    public function __construct(\MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Process\ProcessRunner $processRunner, \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Utils\VersionUtils $versionUtils, \MonorepoBuilder20210705\Symplify\PackageBuilder\Parameter\ParameterProvider $parameterProvider)
    {
        $this->processRunner = $processRunner;
        $this->versionUtils = $versionUtils;
        $this->branchName = $parameterProvider->provideStringParameter(\MonorepoBuilder20210705\Symplify\MonorepoBuilder\ValueObject\Option::DEFAULT_BRANCH_NAME);
    }
    public function work(\MonorepoBuilder20210705\PharIo\Version\Version $version) : void
    {
        $versionInString = $this->getVersionDev($version);
        $gitAddCommitCommand = \sprintf('git add . && git commit --allow-empty -m "open %s" && git push origin "%s"', $versionInString, $this->branchName);
        $this->processRunner->run($gitAddCommitCommand);
    }
    public function getDescription(\MonorepoBuilder20210705\PharIo\Version\Version $version) : string
    {
        $versionInString = $this->getVersionDev($version);
        return \sprintf('Push "%s" open to remote repository', $versionInString);
    }
    private function getVersionDev(\MonorepoBuilder20210705\PharIo\Version\Version $version) : string
    {
        return $this->versionUtils->getNextAliasFormat($version);
    }
}
