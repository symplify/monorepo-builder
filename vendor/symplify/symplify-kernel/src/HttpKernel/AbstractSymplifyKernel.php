<?php

declare (strict_types=1);
namespace MonorepoBuilder202210\Symplify\SymplifyKernel\HttpKernel;

use MonorepoBuilder202210\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use MonorepoBuilder202210\Symfony\Component\DependencyInjection\Container;
use MonorepoBuilder202210\Symfony\Component\DependencyInjection\ContainerInterface;
use MonorepoBuilder202210\Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use MonorepoBuilder202210\Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPass;
use MonorepoBuilder202210\Symplify\SymplifyKernel\Config\Loader\ParameterMergingLoaderFactory;
use MonorepoBuilder202210\Symplify\SymplifyKernel\ContainerBuilderFactory;
use MonorepoBuilder202210\Symplify\SymplifyKernel\Contract\LightKernelInterface;
use MonorepoBuilder202210\Symplify\SymplifyKernel\Exception\ShouldNotHappenException;
use MonorepoBuilder202210\Symplify\SymplifyKernel\ValueObject\SymplifyKernelConfig;
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
    public function getContainer() : \MonorepoBuilder202210\Psr\Container\ContainerInterface
    {
        if (!$this->container instanceof Container) {
            throw new ShouldNotHappenException();
        }
        return $this->container;
    }
}
