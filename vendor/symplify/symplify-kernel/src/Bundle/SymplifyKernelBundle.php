<?php

declare (strict_types=1);
namespace MonorepoBuilder20210706\Symplify\SymplifyKernel\Bundle;

use MonorepoBuilder20210706\Symfony\Component\DependencyInjection\ContainerBuilder;
use MonorepoBuilder20210706\Symfony\Component\HttpKernel\Bundle\Bundle;
use MonorepoBuilder20210706\Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPass;
use MonorepoBuilder20210706\Symplify\SymplifyKernel\DependencyInjection\Extension\SymplifyKernelExtension;
final class SymplifyKernelBundle extends \MonorepoBuilder20210706\Symfony\Component\HttpKernel\Bundle\Bundle
{
    public function build(\MonorepoBuilder20210706\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder) : void
    {
        $containerBuilder->addCompilerPass(new \MonorepoBuilder20210706\Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPass());
    }
    protected function createContainerExtension() : ?\MonorepoBuilder20210706\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
    {
        return new \MonorepoBuilder20210706\Symplify\SymplifyKernel\DependencyInjection\Extension\SymplifyKernelExtension();
    }
}
