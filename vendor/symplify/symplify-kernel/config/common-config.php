<?php

declare (strict_types=1);
namespace MonorepoBuilder202209;

use MonorepoBuilder202209\Symfony\Component\Console\Style\SymfonyStyle;
use MonorepoBuilder202209\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MonorepoBuilder202209\Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory;
use MonorepoBuilder202209\Symplify\PackageBuilder\Parameter\ParameterProvider;
use MonorepoBuilder202209\Symplify\PackageBuilder\Reflection\PrivatesAccessor;
use MonorepoBuilder202209\Symplify\SmartFileSystem\FileSystemFilter;
use MonorepoBuilder202209\Symplify\SmartFileSystem\FileSystemGuard;
use MonorepoBuilder202209\Symplify\SmartFileSystem\Finder\FinderSanitizer;
use MonorepoBuilder202209\Symplify\SmartFileSystem\Finder\SmartFinder;
use MonorepoBuilder202209\Symplify\SmartFileSystem\SmartFileSystem;
use function MonorepoBuilder202209\Symfony\Component\DependencyInjection\Loader\Configurator\service;
return static function (ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire();
    // symfony style
    $services->set(SymfonyStyleFactory::class);
    $services->set(SymfonyStyle::class)->factory([service(SymfonyStyleFactory::class), 'create']);
    // filesystem
    $services->set(FinderSanitizer::class);
    $services->set(SmartFileSystem::class);
    $services->set(SmartFinder::class);
    $services->set(FileSystemGuard::class);
    $services->set(FileSystemFilter::class);
    $services->set(ParameterProvider::class)->args([service('service_container')]);
    $services->set(PrivatesAccessor::class);
};
