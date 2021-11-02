<?php

declare (strict_types=1);
namespace MonorepoBuilder20211102;

use MonorepoBuilder20211102\Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\MonorepoBuilder\Console\MonorepoBuilderConsoleApplication;
use MonorepoBuilder20211102\Symplify\PackageBuilder\Console\Command\CommandNaming;
use MonorepoBuilder20211102\Symplify\PackageBuilder\Reflection\PrivatesCaller;
use MonorepoBuilder20211102\Symplify\PackageBuilder\Yaml\ParametersMerger;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    $services->load('Symplify\MonorepoBuilder\\', __DIR__ . '/../src')->exclude([__DIR__ . '/../src/Exception', __DIR__ . '/../src/Kernel', __DIR__ . '/../src/ValueObject']);
    // console
    $services->set(\Symplify\MonorepoBuilder\Console\MonorepoBuilderConsoleApplication::class);
    $services->alias(\MonorepoBuilder20211102\Symfony\Component\Console\Application::class, \Symplify\MonorepoBuilder\Console\MonorepoBuilderConsoleApplication::class);
    $services->set(\MonorepoBuilder20211102\Symplify\PackageBuilder\Console\Command\CommandNaming::class);
    $services->set(\MonorepoBuilder20211102\Symplify\PackageBuilder\Reflection\PrivatesCaller::class);
    $services->set(\MonorepoBuilder20211102\Symplify\PackageBuilder\Yaml\ParametersMerger::class);
};
