<?php

declare (strict_types=1);
namespace MonorepoBuilder20211223;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $containerConfigurator->import(__DIR__ . '/services.php');
    $containerConfigurator->import(__DIR__ . '/parameters.php');
    $containerConfigurator->import(__DIR__ . '/packages.php');
};
