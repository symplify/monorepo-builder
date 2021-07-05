<?php

declare (strict_types=1);
namespace MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use MonorepoBuilder20210705\PharIo\Version\Version;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Process\ProcessRunner;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\ValueObject\Option;
use MonorepoBuilder20210705\Symplify\PackageBuilder\Parameter\ParameterProvider;
use Throwable;
final class TagVersionReleaseWorker implements \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface
{
    /**
     * @var string
     */
    private $branchName;
    /**
     * @var \Symplify\MonorepoBuilder\Release\Process\ProcessRunner
     */
    private $processRunner;
    public function __construct(\MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Process\ProcessRunner $processRunner, \MonorepoBuilder20210705\Symplify\PackageBuilder\Parameter\ParameterProvider $parameterProvider)
    {
        $this->processRunner = $processRunner;
        $this->branchName = $parameterProvider->provideStringParameter(\MonorepoBuilder20210705\Symplify\MonorepoBuilder\ValueObject\Option::DEFAULT_BRANCH_NAME);
    }
    public function work(\MonorepoBuilder20210705\PharIo\Version\Version $version) : void
    {
        try {
            $gitAddCommitCommand = \sprintf('git add . && git commit -m "prepare release" && git push origin "%s"', $this->branchName);
            $this->processRunner->run($gitAddCommitCommand);
        } catch (\Throwable $exception) {
            // nothing to commit
        }
        $this->processRunner->run('git tag ' . $version->getOriginalString());
    }
    public function getDescription(\MonorepoBuilder20210705\PharIo\Version\Version $version) : string
    {
        return \sprintf('Add local tag "%s"', $version->getOriginalString());
    }
}
