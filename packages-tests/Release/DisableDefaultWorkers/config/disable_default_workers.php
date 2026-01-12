<?php

declare(strict_types=1);

use Symplify\MonorepoBuilder\Config\MBConfig;

return static function (MBConfig $mbConfig): void {
    // Disable default workers - both should be removed
    MBConfig::disableDefaultWorkers();
};
