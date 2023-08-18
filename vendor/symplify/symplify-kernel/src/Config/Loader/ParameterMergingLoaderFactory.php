<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202308\Symplify\SymplifyKernel\Config\Loader;

use MonorepoBuilderPrefix202308\Symfony\Component\Config\FileLocator;
use MonorepoBuilderPrefix202308\Symfony\Component\Config\Loader\DelegatingLoader;
use MonorepoBuilderPrefix202308\Symfony\Component\Config\Loader\GlobFileLoader;
use MonorepoBuilderPrefix202308\Symfony\Component\Config\Loader\LoaderResolver;
use MonorepoBuilderPrefix202308\Symfony\Component\DependencyInjection\ContainerBuilder;
use MonorepoBuilderPrefix202308\Symplify\PackageBuilder\DependencyInjection\FileLoader\ParameterMergingPhpFileLoader;
use MonorepoBuilderPrefix202308\Symplify\SymplifyKernel\Contract\Config\LoaderFactoryInterface;
final class ParameterMergingLoaderFactory implements LoaderFactoryInterface
{
    public function create(ContainerBuilder $containerBuilder, string $currentWorkingDirectory) : \MonorepoBuilderPrefix202308\Symfony\Component\Config\Loader\LoaderInterface
    {
        $fileLocator = new FileLocator([$currentWorkingDirectory]);
        $loaders = [new GlobFileLoader($fileLocator), new ParameterMergingPhpFileLoader($containerBuilder, $fileLocator)];
        $loaderResolver = new LoaderResolver($loaders);
        return new DelegatingLoader($loaderResolver);
    }
}
