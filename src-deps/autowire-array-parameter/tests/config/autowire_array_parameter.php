<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->public();

    $services->load(
        'Symplify\AutowireArrayParameter\Tests\DependencyInjection\CompilerPass\Source\\',
        __DIR__ . '/../DependencyInjection/CompilerPass/Source'
    )
        ->exclude([__DIR__ . '/../DependencyInjection/CompilerPass/Source/SkipMe']);
};
