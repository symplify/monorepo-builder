<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\MonorepoBuilder\Release\Process\ProcessRunner;
use Symplify\MonorepoBuilder\ValueObject\Option;
use MonorepoBuilder20211223\Symplify\PackageBuilder\Parameter\ParameterProvider;
use Throwable;
final class TagVersionReleaseWorker implements \Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface
{
    /**
     * @var string
     */
    private $branchName;
    /**
     * @var \Symplify\MonorepoBuilder\Release\Process\ProcessRunner
     */
    private $processRunner;
    public function __construct(\Symplify\MonorepoBuilder\Release\Process\ProcessRunner $processRunner, \MonorepoBuilder20211223\Symplify\PackageBuilder\Parameter\ParameterProvider $parameterProvider)
    {
        $this->processRunner = $processRunner;
        $this->branchName = $parameterProvider->provideStringParameter(\Symplify\MonorepoBuilder\ValueObject\Option::DEFAULT_BRANCH_NAME);
    }
    public function work(\PharIo\Version\Version $version) : void
    {
        try {
            $gitAddCommitCommand = \sprintf('git add . && git commit -m "prepare release" && git push origin "%s"', $this->branchName);
            $this->processRunner->run($gitAddCommitCommand);
        } catch (\Throwable $exception) {
            // nothing to commit
        }
        $this->processRunner->run('git tag ' . $version->getOriginalString());
    }
    public function getDescription(\PharIo\Version\Version $version) : string
    {
        return \sprintf('Add local tag "%s"', $version->getOriginalString());
    }
}
