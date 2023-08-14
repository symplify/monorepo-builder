<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\MonorepoBuilder\Release\Process\ProcessRunner;
use Symplify\MonorepoBuilder\ValueObject\Option;
use Symplify\PackageBuilder\Parameter\ParameterProvider;
use Throwable;

final class TagVersionReleaseWorker implements ReleaseWorkerInterface
{
    private string $branchName;

    public function __construct(
        private ProcessRunner $processRunner,
        ParameterProvider $parameterProvider
    ) {
        $this->branchName = $parameterProvider->provideStringParameter(Option::DEFAULT_BRANCH_NAME);
    }

    /**
     * @return array<string,callable(): bool|string>
     */
    public function shouldConfirm(): array
    {
        return [
            'whenTrue' => fn(): bool => self::getCurrentBranch() !== self::getDefaultBranch(),
            'message'=> sprintf('Do you want to release it on the [ %s ] branch?',self::getCurrentBranch())
        ];
    }

    public function work(Version $version): void
    {
        try {
            $gitAddCommitCommand = sprintf(
                'git add . && git commit -m "prepare release" && git push origin "%s"',
                $this->branchName
            );

            $this->processRunner->run($gitAddCommitCommand);
        } catch (Throwable) {
            // nothing to commit
        }

        $this->processRunner->run('git tag ' . $version->getOriginalString());
    }

    public function getDescription(Version $version): string
    {
        return sprintf('Add local tag "%s"', $version->getOriginalString());
    }

    private function getCurrentBranch(): string
    {
        exec('git rev-parse --abbrev-ref HEAD',$outputs);

        return $outputs[0];
    }

    private function getDefaultBranch(): string
    {
        exec("git symbolic-ref refs/remotes/origin/HEAD | sed 's@^refs/remotes/origin/@@'",$outputs);

        return $outputs[0];
    }
}
