<?php

declare (strict_types=1);
namespace MonorepoBuilder20211101\Symplify\SymfonyContainerBuilder\Config\Loader;

use MonorepoBuilder20211101\Symfony\Component\Config\FileLocator;
use MonorepoBuilder20211101\Symfony\Component\Config\Loader\DelegatingLoader;
use MonorepoBuilder20211101\Symfony\Component\Config\Loader\GlobFileLoader;
use MonorepoBuilder20211101\Symfony\Component\Config\Loader\LoaderResolver;
use MonorepoBuilder20211101\Symfony\Component\DependencyInjection\ContainerBuilder;
use MonorepoBuilder20211101\Symplify\PackageBuilder\DependencyInjection\FileLoader\ParameterMergingPhpFileLoader;
final class ParameterMergingLoaderFactory
{
    public function create(\MonorepoBuilder20211101\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder, string $currentWorkingDirectory) : \MonorepoBuilder20211101\Symfony\Component\Config\Loader\DelegatingLoader
    {
        $fileLocator = new \MonorepoBuilder20211101\Symfony\Component\Config\FileLocator([$currentWorkingDirectory]);
        $loaders = [new \MonorepoBuilder20211101\Symfony\Component\Config\Loader\GlobFileLoader($fileLocator), new \MonorepoBuilder20211101\Symplify\PackageBuilder\DependencyInjection\FileLoader\ParameterMergingPhpFileLoader($containerBuilder, $fileLocator)];
        $loaderResolver = new \MonorepoBuilder20211101\Symfony\Component\Config\Loader\LoaderResolver($loaders);
        return new \MonorepoBuilder20211101\Symfony\Component\Config\Loader\DelegatingLoader($loaderResolver);
    }
}
