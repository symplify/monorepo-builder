<?php

declare (strict_types=1);
namespace MonorepoBuilder20211103;

use MonorepoBuilder20211103\SebastianBergmann\Diff\Differ;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MonorepoBuilder20211103\Symplify\PackageBuilder\Reflection\PrivatesAccessor;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    $services->load('MonorepoBuilder20211103\Symplify\ConsoleColorDiff\\', __DIR__ . '/../src');
    $services->set(\MonorepoBuilder20211103\SebastianBergmann\Diff\Differ::class);
    $services->set(\MonorepoBuilder20211103\Symplify\PackageBuilder\Reflection\PrivatesAccessor::class);
};
