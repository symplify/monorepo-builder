<?php

declare (strict_types=1);
namespace MonorepoBuilder20220529;

use MonorepoBuilder20220529\Symplify\SymplifyKernel\ValueObject\KernelBootAndApplicationRun;
use MonorepoBuilder20220529\Symplify\VendorPatches\Kernel\VendorPatchesKernel;
$possibleAutoloadPaths = [__DIR__ . '/../autoload.php', __DIR__ . '/../vendor/autoload.php', __DIR__ . '/../../../autoload.php', __DIR__ . '/../../../vendor/autoload.php'];
foreach ($possibleAutoloadPaths as $possibleAutoloadPath) {
    if (!\file_exists($possibleAutoloadPath)) {
        continue;
    }
    require_once $possibleAutoloadPath;
}
$kernelBootAndApplicationRun = new \MonorepoBuilder20220529\Symplify\SymplifyKernel\ValueObject\KernelBootAndApplicationRun(\MonorepoBuilder20220529\Symplify\VendorPatches\Kernel\VendorPatchesKernel::class);
$kernelBootAndApplicationRun->run();
