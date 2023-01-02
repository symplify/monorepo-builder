<?php

declare (strict_types=1);
namespace MonorepoBuilder202301;

use MonorepoBuilder202301\SebastianBergmann\Diff\Differ;
use MonorepoBuilder202301\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MonorepoBuilder202301\Symplify\PackageBuilder\Console\Formatter\ColorConsoleDiffFormatter;
use MonorepoBuilder202301\Symplify\PackageBuilder\Console\Output\ConsoleDiffer;
use MonorepoBuilder202301\Symplify\PackageBuilder\Diff\Output\CompleteUnifiedDiffOutputBuilderFactory;
use MonorepoBuilder202301\Symplify\PackageBuilder\Reflection\PrivatesAccessor;
return static function (ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire();
    $services->set(ColorConsoleDiffFormatter::class);
    $services->set(ConsoleDiffer::class);
    $services->set(CompleteUnifiedDiffOutputBuilderFactory::class);
    $services->set(Differ::class);
    $services->set(PrivatesAccessor::class);
};
