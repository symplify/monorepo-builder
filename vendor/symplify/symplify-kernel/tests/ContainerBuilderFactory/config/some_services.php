<?php

declare (strict_types=1);
namespace MonorepoBuilder202301;

use MonorepoBuilder202301\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MonorepoBuilder202301\Symplify\SmartFileSystem\SmartFileSystem;
return static function (ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(SmartFileSystem::class);
};
