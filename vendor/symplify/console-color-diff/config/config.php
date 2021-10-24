<?php

declare (strict_types=1);
namespace MonorepoBuilder20211024;

use MonorepoBuilder20211024\SebastianBergmann\Diff\Differ;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MonorepoBuilder20211024\Symplify\PackageBuilder\Reflection\PrivatesAccessor;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    $services->load('MonorepoBuilder20211024\Symplify\ConsoleColorDiff\\', __DIR__ . '/../src')->exclude([__DIR__ . '/../src/Bundle']);
    $services->set(\MonorepoBuilder20211024\SebastianBergmann\Diff\Differ::class);
    $services->set(\MonorepoBuilder20211024\Symplify\PackageBuilder\Reflection\PrivatesAccessor::class);
};
