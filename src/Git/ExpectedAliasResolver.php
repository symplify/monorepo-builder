<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Git;

use Symfony\Component\Process\Process;
use Symplify\MonorepoBuilder\Utils\VersionUtils;

final readonly class ExpectedAliasResolver
{
    public function __construct(
        private VersionUtils $versionUtils
    ) {
    }

    public function resolve(): string
    {
        $process = new Process(['git', 'describe', '--abbrev=0', '--tags']);
        $process->run();

        $output = $process->getOutput();

        return $this->versionUtils->getNextAliasFormat($output);
    }
}
