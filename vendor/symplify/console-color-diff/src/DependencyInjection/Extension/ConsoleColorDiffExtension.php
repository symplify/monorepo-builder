<?php

declare (strict_types=1);
namespace MonorepoBuilder20210707\Symplify\ConsoleColorDiff\DependencyInjection\Extension;

use MonorepoBuilder20210707\Symfony\Component\Config\FileLocator;
use MonorepoBuilder20210707\Symfony\Component\DependencyInjection\ContainerBuilder;
use MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Extension\Extension;
use MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
final class ConsoleColorDiffExtension extends \MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Extension\Extension
{
    /**
     * @param string[] $configs
     */
    public function load(array $configs, \MonorepoBuilder20210707\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder) : void
    {
        $phpFileLoader = new \MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Loader\PhpFileLoader($containerBuilder, new \MonorepoBuilder20210707\Symfony\Component\Config\FileLocator(__DIR__ . '/../../../config'));
        $phpFileLoader->load('config.php');
    }
}
