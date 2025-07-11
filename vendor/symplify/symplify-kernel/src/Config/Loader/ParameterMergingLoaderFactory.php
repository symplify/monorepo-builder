<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202507\Symplify\SymplifyKernel\Config\Loader;

use MonorepoBuilderPrefix202507\Symfony\Component\Config\FileLocator;
use MonorepoBuilderPrefix202507\Symfony\Component\Config\Loader\DelegatingLoader;
use MonorepoBuilderPrefix202507\Symfony\Component\Config\Loader\GlobFileLoader;
use MonorepoBuilderPrefix202507\Symfony\Component\Config\Loader\LoaderResolver;
use MonorepoBuilderPrefix202507\Symfony\Component\DependencyInjection\ContainerBuilder;
use MonorepoBuilderPrefix202507\Symplify\PackageBuilder\DependencyInjection\FileLoader\ParameterMergingPhpFileLoader;
use MonorepoBuilderPrefix202507\Symplify\SymplifyKernel\Contract\Config\LoaderFactoryInterface;
final class ParameterMergingLoaderFactory implements LoaderFactoryInterface
{
    public function create(ContainerBuilder $containerBuilder, string $currentWorkingDirectory) : \MonorepoBuilderPrefix202507\Symfony\Component\Config\Loader\LoaderInterface
    {
        $fileLocator = new FileLocator([$currentWorkingDirectory]);
        $loaders = [new GlobFileLoader($fileLocator), new ParameterMergingPhpFileLoader($containerBuilder, $fileLocator)];
        $loaderResolver = new LoaderResolver($loaders);
        return new DelegatingLoader($loaderResolver);
    }
}
