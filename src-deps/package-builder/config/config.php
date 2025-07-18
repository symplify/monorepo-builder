<?php

declare(strict_types=1);

use SebastianBergmann\Diff\Differ;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\PackageBuilder\Console\Formatter\ColorConsoleDiffFormatter;
use Symplify\PackageBuilder\Console\Output\ConsoleDiffer;
use Symplify\PackageBuilder\Diff\DifferFactory;
use Symplify\PackageBuilder\Reflection\PrivatesAccessor;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->public()
        ->autowire();

    $services->set(ColorConsoleDiffFormatter::class);

    $services->set(ConsoleDiffer::class);

    $services->set(DifferFactory::class);
    $services->set(Differ::class)
        ->factory([service(DifferFactory::class), 'create']);

    $services->set(PrivatesAccessor::class);
};
