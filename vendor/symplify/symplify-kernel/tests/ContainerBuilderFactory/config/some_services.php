<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202310;

use MonorepoBuilderPrefix202310\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MonorepoBuilderPrefix202310\Symplify\SmartFileSystem\SmartFileSystem;
return static function (ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(SmartFileSystem::class);
};
