<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202301;

use MonorepoBuilderPrefix202301\SebastianBergmann\Diff\Differ;
use MonorepoBuilderPrefix202301\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MonorepoBuilderPrefix202301\Symplify\PackageBuilder\Console\Formatter\ColorConsoleDiffFormatter;
use MonorepoBuilderPrefix202301\Symplify\PackageBuilder\Console\Output\ConsoleDiffer;
use MonorepoBuilderPrefix202301\Symplify\PackageBuilder\Diff\Output\CompleteUnifiedDiffOutputBuilderFactory;
use MonorepoBuilderPrefix202301\Symplify\PackageBuilder\Reflection\PrivatesAccessor;
return static function (ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire();
    $services->set(ColorConsoleDiffFormatter::class);
    $services->set(ConsoleDiffer::class);
    $services->set(CompleteUnifiedDiffOutputBuilderFactory::class);
    $services->set(Differ::class);
    $services->set(PrivatesAccessor::class);
};
