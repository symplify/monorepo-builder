<?php

declare (strict_types=1);
namespace MonorepoBuilder20220501;

use MonorepoBuilder20220501\Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\MonorepoBuilder\Console\MonorepoBuilderApplication;
use MonorepoBuilder20220501\Symplify\PackageBuilder\Reflection\PrivatesCaller;
use MonorepoBuilder20220501\Symplify\PackageBuilder\Yaml\ParametersMerger;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    $services->load('Symplify\MonorepoBuilder\\', __DIR__ . '/../src')->exclude([__DIR__ . '/../src/Exception', __DIR__ . '/../src/Kernel', __DIR__ . '/../src/ValueObject']);
    // for autowired commands
    $services->alias(\MonorepoBuilder20220501\Symfony\Component\Console\Application::class, \Symplify\MonorepoBuilder\Console\MonorepoBuilderApplication::class);
    $services->set(\MonorepoBuilder20220501\Symplify\PackageBuilder\Reflection\PrivatesCaller::class);
    $services->set(\MonorepoBuilder20220501\Symplify\PackageBuilder\Yaml\ParametersMerger::class);
};
