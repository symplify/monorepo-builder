<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202408\Symplify\SymplifyKernel\DependencyInjection;

use MonorepoBuilderPrefix202408\Symfony\Component\DependencyInjection\Compiler\MergeExtensionConfigurationPass;
use MonorepoBuilderPrefix202408\Symfony\Component\DependencyInjection\ContainerBuilder;
/**
 * Mimics @see \Symfony\Component\HttpKernel\DependencyInjection\MergeExtensionConfigurationPass without dependency on
 * symfony/http-kernel
 */
final class LoadExtensionConfigsCompilerPass extends MergeExtensionConfigurationPass
{
    public function process(ContainerBuilder $containerBuilder) : void
    {
        $extensionNames = \array_keys($containerBuilder->getExtensions());
        foreach ($extensionNames as $extensionName) {
            $containerBuilder->loadFromExtension($extensionName, []);
        }
        parent::process($containerBuilder);
    }
}
