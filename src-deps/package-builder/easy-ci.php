<?php

declare(strict_types=1);

use Symplify\EasyCI\Config\EasyCIConfig;
use Symplify\PackageBuilder\DependencyInjection\CompilerPass\AutowireInterfacesCompilerPass;
use Symplify\PackageBuilder\Diff\DifferFactory;

return static function (EasyCIConfig $easyCIConfig): void {
    $easyCIConfig->typesToSkip([
        DifferFactory::class,
        AutowireInterfacesCompilerPass::class,
    ]);
};
