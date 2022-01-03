<?php

declare (strict_types=1);
namespace MonorepoBuilder20220103;

use MonorepoBuilder20220103\Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MonorepoBuilder20220103\Symplify\ComposerJsonManipulator\ValueObject\Option;
use MonorepoBuilder20220103\Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory;
use MonorepoBuilder20220103\Symplify\PackageBuilder\Parameter\ParameterProvider;
use MonorepoBuilder20220103\Symplify\PackageBuilder\Reflection\PrivatesCaller;
use MonorepoBuilder20220103\Symplify\SmartFileSystem\SmartFileSystem;
use function MonorepoBuilder20220103\Symfony\Component\DependencyInjection\Loader\Configurator\service;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(\MonorepoBuilder20220103\Symplify\ComposerJsonManipulator\ValueObject\Option::INLINE_SECTIONS, ['keywords']);
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    $services->load('MonorepoBuilder20220103\Symplify\ComposerJsonManipulator\\', __DIR__ . '/../src');
    $services->set(\MonorepoBuilder20220103\Symplify\SmartFileSystem\SmartFileSystem::class);
    $services->set(\MonorepoBuilder20220103\Symplify\PackageBuilder\Reflection\PrivatesCaller::class);
    $services->set(\MonorepoBuilder20220103\Symplify\PackageBuilder\Parameter\ParameterProvider::class)->args([\MonorepoBuilder20220103\Symfony\Component\DependencyInjection\Loader\Configurator\service('service_container')]);
    $services->set(\MonorepoBuilder20220103\Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory::class);
    $services->set(\MonorepoBuilder20220103\Symfony\Component\Console\Style\SymfonyStyle::class)->factory([\MonorepoBuilder20220103\Symfony\Component\DependencyInjection\Loader\Configurator\service(\MonorepoBuilder20220103\Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory::class), 'create']);
};
