<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202507;

use MonorepoBuilderPrefix202507\Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPass;
use MonorepoBuilderPrefix202507\Symplify\EasyCI\Config\EasyCIConfig;
return static function (EasyCIConfig $easyCIConfig) : void {
    $easyCIConfig->typesToSkip([AutowireArrayParameterCompilerPass::class]);
};
