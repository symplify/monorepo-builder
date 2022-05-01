<?php

declare (strict_types=1);
namespace MonorepoBuilder20220501;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MonorepoBuilder20220501\Symplify\SmartFileSystem\SmartFileSystem;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\MonorepoBuilder20220501\Symplify\SmartFileSystem\SmartFileSystem::class);
};
