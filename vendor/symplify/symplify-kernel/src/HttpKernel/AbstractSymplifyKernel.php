<?php

declare (strict_types=1);
namespace MonorepoBuilder20220116\Symplify\SymplifyKernel\HttpKernel;

use MonorepoBuilder20220116\Symfony\Component\DependencyInjection\Container;
use MonorepoBuilder20220116\Symfony\Component\DependencyInjection\ContainerInterface;
use MonorepoBuilder20220116\Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPass;
use MonorepoBuilder20220116\Symplify\SymplifyKernel\Config\Loader\ParameterMergingLoaderFactory;
use MonorepoBuilder20220116\Symplify\SymplifyKernel\ContainerBuilderFactory;
use MonorepoBuilder20220116\Symplify\SymplifyKernel\Contract\LightKernelInterface;
use MonorepoBuilder20220116\Symplify\SymplifyKernel\Exception\ShouldNotHappenException;
use MonorepoBuilder20220116\Symplify\SymplifyKernel\ValueObject\SymplifyKernelConfig;
/**
 * @api
 */
abstract class AbstractSymplifyKernel implements \MonorepoBuilder20220116\Symplify\SymplifyKernel\Contract\LightKernelInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\Container|null
     */
    private $container = null;
    /**
     * @param string[] $configFiles
     */
    public function create(array $extensions, array $compilerPasses, array $configFiles) : \MonorepoBuilder20220116\Symfony\Component\DependencyInjection\ContainerInterface
    {
        $containerBuilderFactory = new \MonorepoBuilder20220116\Symplify\SymplifyKernel\ContainerBuilderFactory(new \MonorepoBuilder20220116\Symplify\SymplifyKernel\Config\Loader\ParameterMergingLoaderFactory());
        $compilerPasses[] = new \MonorepoBuilder20220116\Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPass();
        $configFiles[] = \MonorepoBuilder20220116\Symplify\SymplifyKernel\ValueObject\SymplifyKernelConfig::FILE_PATH;
        $containerBuilder = $containerBuilderFactory->create($extensions, $compilerPasses, $configFiles);
        $containerBuilder->compile();
        $this->container = $containerBuilder;
        return $containerBuilder;
    }
    public function getContainer() : \MonorepoBuilder20220116\Psr\Container\ContainerInterface
    {
        if (!$this->container instanceof \MonorepoBuilder20220116\Symfony\Component\DependencyInjection\Container) {
            throw new \MonorepoBuilder20220116\Symplify\SymplifyKernel\Exception\ShouldNotHappenException();
        }
        return $this->container;
    }
}
