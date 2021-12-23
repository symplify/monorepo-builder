<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\MonorepoBuilder\Release\Process\ProcessRunner;
use Symplify\MonorepoBuilder\Utils\VersionUtils;
use Symplify\MonorepoBuilder\ValueObject\Option;
use MonorepoBuilder20211223\Symplify\PackageBuilder\Parameter\ParameterProvider;
final class PushNextDevReleaseWorker implements \Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface
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
    public function __construct(\Symplify\MonorepoBuilder\Release\Process\ProcessRunner $processRunner, \Symplify\MonorepoBuilder\Utils\VersionUtils $versionUtils, \MonorepoBuilder20211223\Symplify\PackageBuilder\Parameter\ParameterProvider $parameterProvider)
    {
        $this->processRunner = $processRunner;
        $this->versionUtils = $versionUtils;
        $this->branchName = $parameterProvider->provideStringParameter(\Symplify\MonorepoBuilder\ValueObject\Option::DEFAULT_BRANCH_NAME);
    }
    public function work(\PharIo\Version\Version $version) : void
    {
        $versionInString = $this->getVersionDev($version);
        $gitAddCommitCommand = \sprintf('git add . && git commit --allow-empty -m "open %s" && git push origin "%s"', $versionInString, $this->branchName);
        $this->processRunner->run($gitAddCommitCommand);
    }
    public function getDescription(\PharIo\Version\Version $version) : string
    {
        $versionInString = $this->getVersionDev($version);
        return \sprintf('Push "%s" open to remote repository', $versionInString);
    }
    private function getVersionDev(\PharIo\Version\Version $version) : string
    {
        return $this->versionUtils->getNextAliasFormat($version);
    }
}
