<?php

// decoupled in own "*.php" file, so ECS, Rector and PHPStan works out of the box here
declare (strict_types=1);
namespace MonorepoBuilder20211223;

use MonorepoBuilder20211223\Symfony\Component\Console\Input\ArgvInput;
use Symplify\MonorepoBuilder\Kernel\MonorepoBuilderKernel;
use Symplify\MonorepoBuilder\ValueObject\File;
use MonorepoBuilder20211223\Symplify\SymplifyKernel\ValueObject\KernelBootAndApplicationRun;
# 1. autoload
$possibleAutoloadPaths = [
    // dependency
    __DIR__ . '/../../../autoload.php',
    // monorepo
    __DIR__ . '/../../../vendor/autoload.php',
];
foreach ($possibleAutoloadPaths as $possibleAutoloadPath) {
    if (\file_exists($possibleAutoloadPath)) {
        require_once $possibleAutoloadPath;
        break;
    }
}
$scoperAutoloadFilepath = __DIR__ . '/../vendor/scoper-autoload.php';
if (\file_exists($scoperAutoloadFilepath)) {
    require_once $scoperAutoloadFilepath;
}
$configFiles = [];
$argvInput = new \MonorepoBuilder20211223\Symfony\Component\Console\Input\ArgvInput();
$configFile = \MonorepoBuilder20211223\resolveConfigFile($argvInput);
if (\is_string($configFile)) {
    $configFiles[] = $configFile;
}
$kernelBootAndApplicationRun = new \MonorepoBuilder20211223\Symplify\SymplifyKernel\ValueObject\KernelBootAndApplicationRun(\Symplify\MonorepoBuilder\Kernel\MonorepoBuilderKernel::class, $configFiles);
$kernelBootAndApplicationRun->run();
function resolveConfigFile(\MonorepoBuilder20211223\Symfony\Component\Console\Input\ArgvInput $argvInput) : ?string
{
    if ($argvInput->hasParameterOption(['-c', '--config'])) {
        $configOption = $argvInput->getParameterOption(['-c', '--config']);
        if (\is_string($configOption) && \file_exists($configOption)) {
            return \realpath($configOption);
        }
    }
    $defaultConfigFilePath = \getcwd() . '/' . \Symplify\MonorepoBuilder\ValueObject\File::CONFIG;
    if (\file_exists($defaultConfigFilePath)) {
        return $defaultConfigFilePath;
    }
    return null;
}
