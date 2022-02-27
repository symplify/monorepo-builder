<?php

declare (strict_types=1);
namespace MonorepoBuilder20220227;

use MonorepoBuilder20220227\Symplify\EasyTesting\Kernel\EasyTestingKernel;
use MonorepoBuilder20220227\Symplify\SymplifyKernel\ValueObject\KernelBootAndApplicationRun;
$possibleAutoloadPaths = [
    // dependency
    __DIR__ . '/../../../autoload.php',
    // after split package
    __DIR__ . '/../vendor/autoload.php',
    // monorepo
    __DIR__ . '/../../../vendor/autoload.php',
];
foreach ($possibleAutoloadPaths as $possibleAutoloadPath) {
    if (\file_exists($possibleAutoloadPath)) {
        require_once $possibleAutoloadPath;
        break;
    }
}
$kernelBootAndApplicationRun = new \MonorepoBuilder20220227\Symplify\SymplifyKernel\ValueObject\KernelBootAndApplicationRun(\MonorepoBuilder20220227\Symplify\EasyTesting\Kernel\EasyTestingKernel::class);
$kernelBootAndApplicationRun->run();
