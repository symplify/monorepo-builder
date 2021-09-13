<?php

declare (strict_types=1);
namespace MonorepoBuilder20210913\Symplify\ConsoleColorDiff\DependencyInjection\Extension;

use MonorepoBuilder20210913\Symfony\Component\Config\FileLocator;
use MonorepoBuilder20210913\Symfony\Component\DependencyInjection\ContainerBuilder;
use MonorepoBuilder20210913\Symfony\Component\DependencyInjection\Extension\Extension;
use MonorepoBuilder20210913\Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
final class ConsoleColorDiffExtension extends \MonorepoBuilder20210913\Symfony\Component\DependencyInjection\Extension\Extension
{
    /**
     * @param string[] $configs
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder
     */
    public function load($configs, $containerBuilder) : void
    {
        $phpFileLoader = new \MonorepoBuilder20210913\Symfony\Component\DependencyInjection\Loader\PhpFileLoader($containerBuilder, new \MonorepoBuilder20210913\Symfony\Component\Config\FileLocator(__DIR__ . '/../../../config'));
        $phpFileLoader->load('config.php');
    }
}
