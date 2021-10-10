<?php

declare (strict_types=1);
namespace MonorepoBuilder20211010;

use MonorepoBuilder20211010\Symplify\EasyTesting\HttpKernel\EasyTestingKernel;
use MonorepoBuilder20211010\Symplify\SymplifyKernel\ValueObject\KernelBootAndApplicationRun;
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
$kernelBootAndApplicationRun = new \MonorepoBuilder20211010\Symplify\SymplifyKernel\ValueObject\KernelBootAndApplicationRun(\MonorepoBuilder20211010\Symplify\EasyTesting\HttpKernel\EasyTestingKernel::class);
$kernelBootAndApplicationRun->run();
