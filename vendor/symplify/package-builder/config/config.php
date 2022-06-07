<?php

declare (strict_types=1);
namespace MonorepoBuilder20220607;

use MonorepoBuilder20220607\SebastianBergmann\Diff\Differ;
use MonorepoBuilder20220607\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MonorepoBuilder20220607\Symplify\PackageBuilder\Console\Formatter\ColorConsoleDiffFormatter;
use MonorepoBuilder20220607\Symplify\PackageBuilder\Console\Output\ConsoleDiffer;
use MonorepoBuilder20220607\Symplify\PackageBuilder\Diff\Output\CompleteUnifiedDiffOutputBuilderFactory;
use MonorepoBuilder20220607\Symplify\PackageBuilder\Reflection\PrivatesAccessor;
return static function (ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire();
    $services->set(ColorConsoleDiffFormatter::class);
    $services->set(ConsoleDiffer::class);
    $services->set(CompleteUnifiedDiffOutputBuilderFactory::class);
    $services->set(Differ::class);
    $services->set(PrivatesAccessor::class);
};
