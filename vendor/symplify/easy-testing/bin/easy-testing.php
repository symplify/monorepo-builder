<?php

declare (strict_types=1);
namespace MonorepoBuilder20211130;

use MonorepoBuilder20211130\Symplify\EasyTesting\Kernel\EasyTestingKernel;
use MonorepoBuilder20211130\Symplify\SymplifyKernel\ValueObject\KernelBootAndApplicationRun;
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
$kernelBootAndApplicationRun = new \MonorepoBuilder20211130\Symplify\SymplifyKernel\ValueObject\KernelBootAndApplicationRun(\MonorepoBuilder20211130\Symplify\EasyTesting\Kernel\EasyTestingKernel::class);
$kernelBootAndApplicationRun->run();
