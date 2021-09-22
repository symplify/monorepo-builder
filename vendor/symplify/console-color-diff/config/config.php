<?php

declare (strict_types=1);
namespace MonorepoBuilder20210922;

use MonorepoBuilder20210922\SebastianBergmann\Diff\Differ;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MonorepoBuilder20210922\Symplify\PackageBuilder\Reflection\PrivatesAccessor;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    $services->load('MonorepoBuilder20210922\Symplify\ConsoleColorDiff\\', __DIR__ . '/../src')->exclude([__DIR__ . '/../src/Bundle']);
    $services->set(\MonorepoBuilder20210922\SebastianBergmann\Diff\Differ::class);
    $services->set(\MonorepoBuilder20210922\Symplify\PackageBuilder\Reflection\PrivatesAccessor::class);
};
