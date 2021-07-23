<?php

declare (strict_types=1);
namespace MonorepoBuilder20210723\Symplify\SymplifyKernel\Bundle;

use MonorepoBuilder20210723\Symfony\Component\DependencyInjection\ContainerBuilder;
use MonorepoBuilder20210723\Symfony\Component\HttpKernel\Bundle\Bundle;
use MonorepoBuilder20210723\Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPass;
use MonorepoBuilder20210723\Symplify\SymplifyKernel\DependencyInjection\Extension\SymplifyKernelExtension;
final class SymplifyKernelBundle extends \MonorepoBuilder20210723\Symfony\Component\HttpKernel\Bundle\Bundle
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder
     */
    public function build($containerBuilder) : void
    {
        $containerBuilder->addCompilerPass(new \MonorepoBuilder20210723\Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPass());
    }
    protected function createContainerExtension() : ?\MonorepoBuilder20210723\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
    {
        return new \MonorepoBuilder20210723\Symplify\SymplifyKernel\DependencyInjection\Extension\SymplifyKernelExtension();
    }
}
