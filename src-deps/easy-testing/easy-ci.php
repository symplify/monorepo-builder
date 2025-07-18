<?php

declare(strict_types=1);

use Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPass;
use Symplify\EasyCI\Config\EasyCIConfig;

return static function (EasyCIConfig $easyCIConfig): void {
    $easyCIConfig->typesToSkip([
        AutowireArrayParameterCompilerPass::class,
    ]);
};
