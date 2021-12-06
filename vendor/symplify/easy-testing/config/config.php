<?php

declare (strict_types=1);
namespace MonorepoBuilder20211206;

use MonorepoBuilder20211206\Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MonorepoBuilder20211206\Symplify\EasyTesting\Command\ValidateFixtureSkipNamingCommand;
use function MonorepoBuilder20211206\Symfony\Component\DependencyInjection\Loader\Configurator\service;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    $services->load('MonorepoBuilder20211206\Symplify\EasyTesting\\', __DIR__ . '/../src')->exclude([__DIR__ . '/../src/DataProvider', __DIR__ . '/../src/Kernel', __DIR__ . '/../src/ValueObject']);
    // console
    $services->set(\MonorepoBuilder20211206\Symfony\Component\Console\Application::class)->call('add', [\MonorepoBuilder20211206\Symfony\Component\DependencyInjection\Loader\Configurator\service(\MonorepoBuilder20211206\Symplify\EasyTesting\Command\ValidateFixtureSkipNamingCommand::class)]);
};
