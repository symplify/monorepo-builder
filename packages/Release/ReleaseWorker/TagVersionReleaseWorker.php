<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\MonorepoBuilder\Release\Process\ProcessRunner;
use Symplify\MonorepoBuilder\ValueObject\Option;
use MonorepoBuilderPrefix202311\Symplify\PackageBuilder\Parameter\ParameterProvider;
use Throwable;
final class TagVersionReleaseWorker implements ReleaseWorkerInterface
{
    /**
     * @var \Symplify\MonorepoBuilder\Release\Process\ProcessRunner
     */
    private $processRunner;
    /**
     * @var string
     */
    private $branchName;
    public function __construct(ProcessRunner $processRunner, ParameterProvider $parameterProvider)
    {
        $this->processRunner = $processRunner;
        $this->branchName = $parameterProvider->provideStringParameter(Option::DEFAULT_BRANCH_NAME);
    }
    /**
     * @return array<string,callable(): bool|string>
     */
    public function shouldConfirm() : array
    {
        return ['whenTrue' => function () : bool {
            return self::getDefaultBranch() !== null && self::getCurrentBranch() !== self::getDefaultBranch();
        }, 'message' => \sprintf('Do you want to release it on the [ %s ] branch?', self::getCurrentBranch())];
    }
    public function work(Version $version) : void
    {
        try {
            $gitAddCommitCommand = \sprintf('git add . && git commit -m "prepare release" && git push origin "%s"', $this->branchName);
            $this->processRunner->run($gitAddCommitCommand);
        } catch (Throwable $exception) {
            // nothing to commit
        }
        $this->processRunner->run('git tag ' . $version->getOriginalString());
    }
    public function getDescription(Version $version) : string
    {
        return \sprintf('Add local tag "%s"', $version->getOriginalString());
    }
    private function getCurrentBranch() : ?string
    {
        \exec('git rev-parse --abbrev-ref HEAD', $outputs, $result_code);
        return $result_code === 0 ? $outputs[0] : null;
    }
    private function getDefaultBranch() : ?string
    {
        \exec('git remote set-head origin -a');
        \exec("git symbolic-ref --short refs/remotes/origin/HEAD | cut -d '/' -f 2", $outputs, $result_code);
        return $result_code === 0 ? $outputs[0] ?? null : null;
    }
}
