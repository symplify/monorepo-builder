<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Tests\Release\DisableDefaultWorkers\Fixture;

use PharIo\Version\Version;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;

final readonly class GenerateChangelogReleaseWorker implements ReleaseWorkerInterface
{
    public function work(Version $version): void
    {
    }

    public function getDescription(Version $version): string
    {
        return 'Generate changelog from conventional commits';
    }
}
