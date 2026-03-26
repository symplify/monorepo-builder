<?php

declare(strict_types=1);

use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushTagReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\TagVersionReleaseWorker;
use Symplify\MonorepoBuilder\Tests\Release\DisableDefaultWorkers\Fixture\FetchTagsReleaseWorker;
use Symplify\MonorepoBuilder\Tests\Release\DisableDefaultWorkers\Fixture\GenerateChangelogReleaseWorker;

/**
 * Reproduces the exact scenario from issue #111:
 * User wants custom workers to run BEFORE the default tag/push workers.
 *
 * @see https://github.com/symplify/monorepo-builder/issues/111
 */
return static function (MBConfig $mbConfig): void {
    MBConfig::disableDefaultWorkers();

    $mbConfig->workers([
        FetchTagsReleaseWorker::class,
        GenerateChangelogReleaseWorker::class,
        TagVersionReleaseWorker::class,
        PushTagReleaseWorker::class,
    ]);
};
