<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202311\Symplify\SymplifyKernel\HttpKernel;

use MonorepoBuilderPrefix202311\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use MonorepoBuilderPrefix202311\Symfony\Component\DependencyInjection\Container;
use MonorepoBuilderPrefix202311\Symfony\Component\DependencyInjection\ContainerInterface;
use MonorepoBuilderPrefix202311\Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use MonorepoBuilderPrefix202311\Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPass;
use MonorepoBuilderPrefix202311\Symplify\SymplifyKernel\Config\Loader\ParameterMergingLoaderFactory;
use MonorepoBuilderPrefix202311\Symplify\SymplifyKernel\ContainerBuilderFactory;
use MonorepoBuilderPrefix202311\Symplify\SymplifyKernel\Contract\LightKernelInterface;
use MonorepoBuilderPrefix202311\Symplify\SymplifyKernel\Exception\ShouldNotHappenException;
use MonorepoBuilderPrefix202311\Symplify\SymplifyKernel\ValueObject\SymplifyKernelConfig;
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
    public function getContainer() : \MonorepoBuilderPrefix202311\Psr\Container\ContainerInterface
    {
        if (!$this->container instanceof Container) {
            throw new ShouldNotHappenException();
        }
        return $this->container;
    }
}
