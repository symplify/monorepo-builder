<?php

declare (strict_types=1);
namespace MonorepoBuilder20220530;

use MonorepoBuilder20220530\SebastianBergmann\Diff\Differ;
use MonorepoBuilder20220530\SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;
use MonorepoBuilder20220530\Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MonorepoBuilder20220530\Symplify\PackageBuilder\Composer\VendorDirProvider;
use MonorepoBuilder20220530\Symplify\PackageBuilder\Yaml\ParametersMerger;
use MonorepoBuilder20220530\Symplify\SmartFileSystem\Json\JsonFileSystem;
use MonorepoBuilder20220530\Symplify\VendorPatches\Console\VendorPatchesApplication;
use function MonorepoBuilder20220530\Symfony\Component\DependencyInjection\Loader\Configurator\service;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    $services->load('MonorepoBuilder20220530\Symplify\VendorPatches\\', __DIR__ . '/../src')->exclude([__DIR__ . '/../src/Kernel', __DIR__ . '/../src/ValueObject']);
    $services->set(\MonorepoBuilder20220530\SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder::class)->args(['$addLineNumbers' => \true]);
    $services->set(\MonorepoBuilder20220530\SebastianBergmann\Diff\Differ::class)->args(['$outputBuilder' => \MonorepoBuilder20220530\Symfony\Component\DependencyInjection\Loader\Configurator\service(\MonorepoBuilder20220530\SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder::class)]);
    $services->set(\MonorepoBuilder20220530\Symplify\PackageBuilder\Composer\VendorDirProvider::class);
    $services->set(\MonorepoBuilder20220530\Symplify\SmartFileSystem\Json\JsonFileSystem::class);
    // for autowired commands
    $services->alias(\MonorepoBuilder20220530\Symfony\Component\Console\Application::class, \MonorepoBuilder20220530\Symplify\VendorPatches\Console\VendorPatchesApplication::class);
    $services->set(\MonorepoBuilder20220530\Symplify\PackageBuilder\Yaml\ParametersMerger::class);
};
