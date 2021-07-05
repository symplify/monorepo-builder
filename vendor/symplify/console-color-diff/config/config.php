<?php

declare (strict_types=1);
namespace MonorepoBuilder20210705;

use MonorepoBuilder20210705\SebastianBergmann\Diff\Differ;
use MonorepoBuilder20210705\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MonorepoBuilder20210705\Symplify\PackageBuilder\Reflection\PrivatesAccessor;
return static function (\MonorepoBuilder20210705\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    $services->load('MonorepoBuilder20210705\Symplify\ConsoleColorDiff\\', __DIR__ . '/../src')->exclude([__DIR__ . '/../src/Bundle']);
    $services->set(\MonorepoBuilder20210705\SebastianBergmann\Diff\Differ::class);
    $services->set(\MonorepoBuilder20210705\Symplify\PackageBuilder\Reflection\PrivatesAccessor::class);
};
