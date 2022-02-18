<?php

declare (strict_types=1);
namespace MonorepoBuilder20220218\Symplify\SymplifyKernel\HttpKernel;

use MonorepoBuilder20220218\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use MonorepoBuilder20220218\Symfony\Component\DependencyInjection\Container;
use MonorepoBuilder20220218\Symfony\Component\DependencyInjection\ContainerInterface;
use MonorepoBuilder20220218\Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use MonorepoBuilder20220218\Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPass;
use MonorepoBuilder20220218\Symplify\SymplifyKernel\Config\Loader\ParameterMergingLoaderFactory;
use MonorepoBuilder20220218\Symplify\SymplifyKernel\ContainerBuilderFactory;
use MonorepoBuilder20220218\Symplify\SymplifyKernel\Contract\LightKernelInterface;
use MonorepoBuilder20220218\Symplify\SymplifyKernel\Exception\ShouldNotHappenException;
use MonorepoBuilder20220218\Symplify\SymplifyKernel\ValueObject\SymplifyKernelConfig;
/**
 * @api
 */
abstract class AbstractSymplifyKernel implements \MonorepoBuilder20220218\Symplify\SymplifyKernel\Contract\LightKernelInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\Container|null
     */
    private $container = null;
    /**
     * @param string[] $configFiles
     * @param CompilerPassInterface[] $compilerPasses
     * @param ExtensionInterface[] $extensions
     */
    public function create(array $configFiles, array $compilerPasses = [], array $extensions = []) : \MonorepoBuilder20220218\Symfony\Component\DependencyInjection\ContainerInterface
    {
        $containerBuilderFactory = new \MonorepoBuilder20220218\Symplify\SymplifyKernel\ContainerBuilderFactory(new \MonorepoBuilder20220218\Symplify\SymplifyKernel\Config\Loader\ParameterMergingLoaderFactory());
        $compilerPasses[] = new \MonorepoBuilder20220218\Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPass();
        $configFiles[] = \MonorepoBuilder20220218\Symplify\SymplifyKernel\ValueObject\SymplifyKernelConfig::FILE_PATH;
        $containerBuilder = $containerBuilderFactory->create($configFiles, $compilerPasses, $extensions);
        $containerBuilder->compile();
        $this->container = $containerBuilder;
        return $containerBuilder;
    }
    public function getContainer() : \MonorepoBuilder20220218\Psr\Container\ContainerInterface
    {
        if (!$this->container instanceof \MonorepoBuilder20220218\Symfony\Component\DependencyInjection\Container) {
            throw new \MonorepoBuilder20220218\Symplify\SymplifyKernel\Exception\ShouldNotHappenException();
        }
        return $this->container;
    }
}
