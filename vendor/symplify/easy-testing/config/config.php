<?php

declare (strict_types=1);
namespace MonorepoBuilder20220610;

use MonorepoBuilder20220610\Symfony\Component\Console\Application;
use MonorepoBuilder20220610\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MonorepoBuilder20220610\Symplify\EasyTesting\Command\ValidateFixtureSkipNamingCommand;
use function MonorepoBuilder20220610\Symfony\Component\DependencyInjection\Loader\Configurator\service;
return static function (ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire();
    $services->load('MonorepoBuilder20220610\Symplify\EasyTesting\\', __DIR__ . '/../src')->exclude([__DIR__ . '/../src/DataProvider', __DIR__ . '/../src/Kernel', __DIR__ . '/../src/ValueObject']);
    // console
    $services->set(Application::class)->call('add', [service(ValidateFixtureSkipNamingCommand::class)]);
};
