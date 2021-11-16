<?php

declare (strict_types=1);
namespace MonorepoBuilder20211116;

use MonorepoBuilder20211116\SebastianBergmann\Diff\Differ;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MonorepoBuilder20211116\Symplify\PackageBuilder\Reflection\PrivatesAccessor;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    $services->load('MonorepoBuilder20211116\Symplify\ConsoleColorDiff\\', __DIR__ . '/../src');
    $services->set(\MonorepoBuilder20211116\SebastianBergmann\Diff\Differ::class);
    $services->set(\MonorepoBuilder20211116\Symplify\PackageBuilder\Reflection\PrivatesAccessor::class);
};
