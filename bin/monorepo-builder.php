<?php

// decoupled in own "*.php" file, so ECS, Rector and PHPStan works out of the box here
declare (strict_types=1);
namespace MonorepoBuilder20210913;

use MonorepoBuilder20210913\Symfony\Component\Console\Input\ArgvInput;
use Symplify\MonorepoBuilder\HttpKernel\MonorepoBuilderKernel;
use Symplify\MonorepoBuilder\ValueObject\File;
use MonorepoBuilder20210913\Symplify\SmartFileSystem\SmartFileInfo;
use MonorepoBuilder20210913\Symplify\SymplifyKernel\ValueObject\KernelBootAndApplicationRun;
# 1. autoload
$possibleAutoloadPaths = [
    // monorepo
    __DIR__ . '/../../../vendor/autoload.php',
    // after split package
    __DIR__ . '/../vendor/autoload.php',
    // dependency
    __DIR__ . '/../../../autoload.php',
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
$configFileInfos = [];
$argvInput = new \MonorepoBuilder20210913\Symfony\Component\Console\Input\ArgvInput();
$configFileInfo = \MonorepoBuilder20210913\resolveConfigFileInfo($argvInput);
if ($configFileInfo instanceof \MonorepoBuilder20210913\Symplify\SmartFileSystem\SmartFileInfo) {
    $configFileInfos[] = $configFileInfo;
}
$kernelBootAndApplicationRun = new \MonorepoBuilder20210913\Symplify\SymplifyKernel\ValueObject\KernelBootAndApplicationRun(\Symplify\MonorepoBuilder\HttpKernel\MonorepoBuilderKernel::class, $configFileInfos);
$kernelBootAndApplicationRun->run();
function resolveConfigFileInfo(\MonorepoBuilder20210913\Symfony\Component\Console\Input\ArgvInput $argvInput) : ?\MonorepoBuilder20210913\Symplify\SmartFileSystem\SmartFileInfo
{
    if ($argvInput->hasParameterOption(['-c', '--config'])) {
        $configOption = $argvInput->getParameterOption(['-c', '--config']);
        if (\is_string($configOption) && \file_exists($configOption)) {
            return new \MonorepoBuilder20210913\Symplify\SmartFileSystem\SmartFileInfo($configOption);
        }
    }
    $defaultConfigFilePath = \getcwd() . '/' . \Symplify\MonorepoBuilder\ValueObject\File::CONFIG;
    if (\file_exists($defaultConfigFilePath)) {
        return new \MonorepoBuilder20210913\Symplify\SmartFileSystem\SmartFileInfo($defaultConfigFilePath);
    }
    return null;
}
