<?php

declare (strict_types=1);
namespace MonorepoBuilder20210824;

use MonorepoBuilder20210824\SebastianBergmann\Diff\Differ;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MonorepoBuilder20210824\Symplify\PackageBuilder\Reflection\PrivatesAccessor;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    $services->load('MonorepoBuilder20210824\Symplify\ConsoleColorDiff\\', __DIR__ . '/../src')->exclude([__DIR__ . '/../src/Bundle']);
    $services->set(\MonorepoBuilder20210824\SebastianBergmann\Diff\Differ::class);
    $services->set(\MonorepoBuilder20210824\Symplify\PackageBuilder\Reflection\PrivatesAccessor::class);
};
