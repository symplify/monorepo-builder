<?php

declare (strict_types=1);
namespace MonorepoBuilder20210913;

use MonorepoBuilder20210913\Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MonorepoBuilder20210913\Symplify\EasyTesting\Console\EasyTestingConsoleApplication;
use MonorepoBuilder20210913\Symplify\PackageBuilder\Console\Command\CommandNaming;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    $services->load('MonorepoBuilder20210913\Symplify\EasyTesting\\', __DIR__ . '/../src')->exclude([__DIR__ . '/../src/DataProvider', __DIR__ . '/../src/HttpKernel', __DIR__ . '/../src/ValueObject']);
    // console
    $services->set(\MonorepoBuilder20210913\Symplify\EasyTesting\Console\EasyTestingConsoleApplication::class);
    $services->alias(\MonorepoBuilder20210913\Symfony\Component\Console\Application::class, \MonorepoBuilder20210913\Symplify\EasyTesting\Console\EasyTestingConsoleApplication::class);
    $services->set(\MonorepoBuilder20210913\Symplify\PackageBuilder\Console\Command\CommandNaming::class);
};
