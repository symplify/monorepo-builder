<?php

declare(strict_types=1);

use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\TagVersionReleaseWorker;

return static function (MBConfig $mbConfig): void {
    // Disable default workers
    MBConfig::disableDefaultWorkers();

    // But user explicitly wants TagVersionReleaseWorker
    // This should be preserved because user registration replaces the tagged default
    $mbConfig->workers([
        TagVersionReleaseWorker::class,
    ]);
};
