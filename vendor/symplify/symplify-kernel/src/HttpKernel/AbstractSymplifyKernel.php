<?php

declare (strict_types=1);
namespace MonorepoBuilder202208\Symplify\SymplifyKernel\HttpKernel;

use MonorepoBuilder202208\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use MonorepoBuilder202208\Symfony\Component\DependencyInjection\Container;
use MonorepoBuilder202208\Symfony\Component\DependencyInjection\ContainerInterface;
use MonorepoBuilder202208\Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use MonorepoBuilder202208\Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPass;
use MonorepoBuilder202208\Symplify\SymplifyKernel\Config\Loader\ParameterMergingLoaderFactory;
use MonorepoBuilder202208\Symplify\SymplifyKernel\ContainerBuilderFactory;
use MonorepoBuilder202208\Symplify\SymplifyKernel\Contract\LightKernelInterface;
use MonorepoBuilder202208\Symplify\SymplifyKernel\Exception\ShouldNotHappenException;
use MonorepoBuilder202208\Symplify\SymplifyKernel\ValueObject\SymplifyKernelConfig;
/**
 * @api
 */
abstract class AbstractSymplifyKernel implements LightKernelInterface
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
    public function create(array $configFiles, array $compilerPasses = [], array $extensions = []) : ContainerInterface
    {
        $containerBuilderFactory = new ContainerBuilderFactory(new ParameterMergingLoaderFactory());
        $compilerPasses[] = new AutowireArrayParameterCompilerPass();
        $configFiles[] = SymplifyKernelConfig::FILE_PATH;
        $containerBuilder = $containerBuilderFactory->create($configFiles, $compilerPasses, $extensions);
        $containerBuilder->compile();
        $this->container = $containerBuilder;
        return $containerBuilder;
    }
    public function getContainer() : \MonorepoBuilder202208\Psr\Container\ContainerInterface
    {
        if (!$this->container instanceof Container) {
            throw new ShouldNotHappenException();
        }
        return $this->container;
    }
}
