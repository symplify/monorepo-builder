<?php

declare (strict_types=1);
namespace MonorepoBuilder20211023\Symplify\SymplifyKernel\DependencyInjection\Extension;

use MonorepoBuilder20211023\Symfony\Component\Config\FileLocator;
use MonorepoBuilder20211023\Symfony\Component\DependencyInjection\ContainerBuilder;
use MonorepoBuilder20211023\Symfony\Component\DependencyInjection\Extension\Extension;
use MonorepoBuilder20211023\Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
final class SymplifyKernelExtension extends \MonorepoBuilder20211023\Symfony\Component\DependencyInjection\Extension\Extension
{
    /**
     * @param string[] $configs
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder
     */
    public function load($configs, $containerBuilder) : void
    {
        $phpFileLoader = new \MonorepoBuilder20211023\Symfony\Component\DependencyInjection\Loader\PhpFileLoader($containerBuilder, new \MonorepoBuilder20211023\Symfony\Component\Config\FileLocator(__DIR__ . '/../../../config'));
        $phpFileLoader->load('common-config.php');
    }
}
