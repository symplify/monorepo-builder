<?php

declare(strict_types=1);

use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\Merge\JsonSchema;

return static function (MBConfig $mbConfig): void {
    $mbConfig->composerSectionOrder(JsonSchema::getProperties());
};
