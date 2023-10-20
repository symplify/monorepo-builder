<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202310;

use MonorepoBuilderPrefix202310\Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPass;
use MonorepoBuilderPrefix202310\Symplify\EasyCI\Config\EasyCIConfig;
return static function (EasyCIConfig $easyCIConfig) : void {
    $easyCIConfig->typesToSkip([AutowireArrayParameterCompilerPass::class]);
};
