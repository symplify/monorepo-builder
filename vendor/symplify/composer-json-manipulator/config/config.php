<?php

declare (strict_types=1);
namespace MonorepoBuilder202209;

use MonorepoBuilder202209\Symfony\Component\Console\Style\SymfonyStyle;
use MonorepoBuilder202209\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MonorepoBuilder202209\Symplify\ComposerJsonManipulator\ValueObject\Option;
use MonorepoBuilder202209\Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory;
use MonorepoBuilder202209\Symplify\PackageBuilder\Parameter\ParameterProvider;
use MonorepoBuilder202209\Symplify\PackageBuilder\Reflection\PrivatesCaller;
use MonorepoBuilder202209\Symplify\SmartFileSystem\SmartFileSystem;
use function MonorepoBuilder202209\Symfony\Component\DependencyInjection\Loader\Configurator\service;
return static function (ContainerConfigurator $containerConfigurator) : void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::INLINE_SECTIONS, ['keywords']);
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire();
    $services->load('MonorepoBuilder202209\Symplify\ComposerJsonManipulator\\', __DIR__ . '/../src');
    $services->set(SmartFileSystem::class);
    $services->set(PrivatesCaller::class);
    $services->set(ParameterProvider::class)->args([service('service_container')]);
    $services->set(SymfonyStyleFactory::class);
    $services->set(SymfonyStyle::class)->factory([service(SymfonyStyleFactory::class), 'create']);
};
