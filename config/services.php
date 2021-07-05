<?php

declare (strict_types=1);
namespace MonorepoBuilder20210705;

use MonorepoBuilder20210705\Symfony\Component\Console\Application;
use MonorepoBuilder20210705\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Console\MonorepoBuilderConsoleApplication;
use MonorepoBuilder20210705\Symplify\PackageBuilder\Console\Command\CommandNaming;
use MonorepoBuilder20210705\Symplify\PackageBuilder\Reflection\PrivatesCaller;
use MonorepoBuilder20210705\Symplify\PackageBuilder\Yaml\ParametersMerger;
return static function (\MonorepoBuilder20210705\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    $services->load('MonorepoBuilder20210705\Symplify\MonorepoBuilder\\', __DIR__ . '/../src')->exclude([__DIR__ . '/../src/Exception', __DIR__ . '/../src/HttpKernel', __DIR__ . '/../src/ValueObject']);
    // console
    $services->set(\MonorepoBuilder20210705\Symplify\MonorepoBuilder\Console\MonorepoBuilderConsoleApplication::class);
    $services->alias(\MonorepoBuilder20210705\Symfony\Component\Console\Application::class, \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Console\MonorepoBuilderConsoleApplication::class);
    $services->set(\MonorepoBuilder20210705\Symplify\PackageBuilder\Console\Command\CommandNaming::class);
    $services->set(\MonorepoBuilder20210705\Symplify\PackageBuilder\Reflection\PrivatesCaller::class);
    $services->set(\MonorepoBuilder20210705\Symplify\PackageBuilder\Yaml\ParametersMerger::class);
};
