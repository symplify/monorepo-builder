<?php

declare (strict_types=1);
namespace MonorepoBuilder20210706;

use MonorepoBuilder20210706\SebastianBergmann\Diff\Differ;
use MonorepoBuilder20210706\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MonorepoBuilder20210706\Symplify\PackageBuilder\Reflection\PrivatesAccessor;
return static function (\MonorepoBuilder20210706\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    $services->load('MonorepoBuilder20210706\Symplify\ConsoleColorDiff\\', __DIR__ . '/../src')->exclude([__DIR__ . '/../src/Bundle']);
    $services->set(\MonorepoBuilder20210706\SebastianBergmann\Diff\Differ::class);
    $services->set(\MonorepoBuilder20210706\Symplify\PackageBuilder\Reflection\PrivatesAccessor::class);
};
