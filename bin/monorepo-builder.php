<?php

// decoupled in own "*.php" file, so ECS, Rector and PHPStan works out of the box here
declare (strict_types=1);
namespace MonorepoBuilderPrefix202408;

use MonorepoBuilderPrefix202408\Symfony\Component\Console\Input\ArgvInput;
use Symplify\MonorepoBuilder\Kernel\MonorepoBuilderKernel;
use Symplify\MonorepoBuilder\ValueObject\File;
use MonorepoBuilderPrefix202408\Symplify\SymplifyKernel\ValueObject\KernelBootAndApplicationRun;
\define('MonorepoBuilderPrefix202408\\__MONOREPO_BUILDER_RUNNING__', \true);
# 1. autoload
$possibleAutoloadPaths = [
    // local
    __DIR__ . '/../vendor/autoload.php',
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
$argvInput = new ArgvInput();
$configFile = resolveConfigFile($argvInput);
if (\is_string($configFile)) {
    $configFiles[] = $configFile;
}
$kernelBootAndApplicationRun = new KernelBootAndApplicationRun(MonorepoBuilderKernel::class, $configFiles);
$kernelBootAndApplicationRun->run();
function resolveConfigFile(ArgvInput $argvInput) : ?string
{
    if ($argvInput->hasParameterOption(['-c', '--config'])) {
        $configOption = $argvInput->getParameterOption(['-c', '--config']);
        if (\is_string($configOption) && \file_exists($configOption)) {
            return \realpath($configOption);
        }
    }
    $defaultConfigFilePath = \getcwd() . '/' . File::CONFIG;
    if (\file_exists($defaultConfigFilePath)) {
        return $defaultConfigFilePath;
    }
    return null;
}
