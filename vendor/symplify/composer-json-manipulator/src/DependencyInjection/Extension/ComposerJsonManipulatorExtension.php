<?php

declare (strict_types=1);
namespace MonorepoBuilder20210911\Symplify\ComposerJsonManipulator\DependencyInjection\Extension;

use MonorepoBuilder20210911\Symfony\Component\Config\FileLocator;
use MonorepoBuilder20210911\Symfony\Component\DependencyInjection\ContainerBuilder;
use MonorepoBuilder20210911\Symfony\Component\DependencyInjection\Extension\Extension;
use MonorepoBuilder20210911\Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
final class ComposerJsonManipulatorExtension extends \MonorepoBuilder20210911\Symfony\Component\DependencyInjection\Extension\Extension
{
    /**
     * @param string[] $configs
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder
     */
    public function load($configs, $containerBuilder) : void
    {
        $phpFileLoader = new \MonorepoBuilder20210911\Symfony\Component\DependencyInjection\Loader\PhpFileLoader($containerBuilder, new \MonorepoBuilder20210911\Symfony\Component\Config\FileLocator(__DIR__ . '/../../../config'));
        $phpFileLoader->load('config.php');
    }
}
