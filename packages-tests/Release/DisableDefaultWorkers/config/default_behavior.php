<?php

declare(strict_types=1);

use Symplify\MonorepoBuilder\Config\MBConfig;

return static function (MBConfig $mbConfig): void {
    // Default behavior - don't disable default workers
    $mbConfig->defaultBranch('main');
};
