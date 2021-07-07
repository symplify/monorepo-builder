<?php

declare (strict_types=1);
namespace MonorepoBuilder20210707;

use MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    $services->load('Symplify\\MonorepoBuilder\\', __DIR__ . '/../packages')->exclude([
        // register manually
        __DIR__ . '/../packages/Release/ReleaseWorker',
    ]);
};
