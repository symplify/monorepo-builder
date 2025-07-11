<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202507;

use MonorepoBuilderPrefix202507\SebastianBergmann\Diff\Differ;
use MonorepoBuilderPrefix202507\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MonorepoBuilderPrefix202507\Symplify\PackageBuilder\Console\Formatter\ColorConsoleDiffFormatter;
use MonorepoBuilderPrefix202507\Symplify\PackageBuilder\Console\Output\ConsoleDiffer;
use MonorepoBuilderPrefix202507\Symplify\PackageBuilder\Diff\DifferFactory;
use MonorepoBuilderPrefix202507\Symplify\PackageBuilder\Reflection\PrivatesAccessor;
use function MonorepoBuilderPrefix202507\Symfony\Component\DependencyInjection\Loader\Configurator\service;
return static function (ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire();
    $services->set(ColorConsoleDiffFormatter::class);
    $services->set(ConsoleDiffer::class);
    $services->set(DifferFactory::class);
    $services->set(Differ::class)->factory([service(DifferFactory::class), 'create']);
    $services->set(PrivatesAccessor::class);
};
