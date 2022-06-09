<?php

declare (strict_types=1);
namespace MonorepoBuilder20220609\Symplify\SymplifyKernel\Config\Loader;

use MonorepoBuilder20220609\Symfony\Component\Config\FileLocator;
use MonorepoBuilder20220609\Symfony\Component\Config\Loader\DelegatingLoader;
use MonorepoBuilder20220609\Symfony\Component\Config\Loader\GlobFileLoader;
use MonorepoBuilder20220609\Symfony\Component\Config\Loader\LoaderResolver;
use MonorepoBuilder20220609\Symfony\Component\DependencyInjection\ContainerBuilder;
use MonorepoBuilder20220609\Symplify\PackageBuilder\DependencyInjection\FileLoader\ParameterMergingPhpFileLoader;
use MonorepoBuilder20220609\Symplify\SymplifyKernel\Contract\Config\LoaderFactoryInterface;
final class ParameterMergingLoaderFactory implements LoaderFactoryInterface
{
    public function create(ContainerBuilder $containerBuilder, string $currentWorkingDirectory) : \MonorepoBuilder20220609\Symfony\Component\Config\Loader\LoaderInterface
    {
        $fileLocator = new FileLocator([$currentWorkingDirectory]);
        $loaders = [new GlobFileLoader($fileLocator), new ParameterMergingPhpFileLoader($containerBuilder, $fileLocator)];
        $loaderResolver = new LoaderResolver($loaders);
        return new DelegatingLoader($loaderResolver);
    }
}
