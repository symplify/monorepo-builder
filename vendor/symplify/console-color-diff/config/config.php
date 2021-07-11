<?php

declare (strict_types=1);
namespace MonorepoBuilder20210711;

use MonorepoBuilder20210711\SebastianBergmann\Diff\Differ;
use MonorepoBuilder20210711\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MonorepoBuilder20210711\Symplify\PackageBuilder\Reflection\PrivatesAccessor;
return static function (\MonorepoBuilder20210711\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    $services->load('MonorepoBuilder20210711\Symplify\ConsoleColorDiff\\', __DIR__ . '/../src')->exclude([__DIR__ . '/../src/Bundle']);
    $services->set(\MonorepoBuilder20210711\SebastianBergmann\Diff\Differ::class);
    $services->set(\MonorepoBuilder20210711\Symplify\PackageBuilder\Reflection\PrivatesAccessor::class);
};
