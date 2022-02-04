<?php

declare (strict_types=1);
namespace MonorepoBuilder20220204\Symplify\SymplifyKernel;

use MonorepoBuilder20220204\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use MonorepoBuilder20220204\Symfony\Component\DependencyInjection\ContainerBuilder;
use MonorepoBuilder20220204\Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use MonorepoBuilder20220204\Symplify\SymplifyKernel\Contract\Config\LoaderFactoryInterface;
use MonorepoBuilder20220204\Symplify\SymplifyKernel\DependencyInjection\LoadExtensionConfigsCompilerPass;
use MonorepoBuilder20220204\Webmozart\Assert\Assert;
/**
 * @see \Symplify\SymplifyKernel\Tests\ContainerBuilderFactory\ContainerBuilderFactoryTest
 */
final class ContainerBuilderFactory
{
    /**
     * @var \Symplify\SymplifyKernel\Contract\Config\LoaderFactoryInterface
     */
    private $loaderFactory;
    public function __construct(\MonorepoBuilder20220204\Symplify\SymplifyKernel\Contract\Config\LoaderFactoryInterface $loaderFactory)
    {
        $this->loaderFactory = $loaderFactory;
    }
    /**
     * @param string[] $configFiles
     * @param CompilerPassInterface[] $compilerPasses
     * @param ExtensionInterface[] $extensions
     */
    public function create(array $configFiles, array $compilerPasses, array $extensions) : \MonorepoBuilder20220204\Symfony\Component\DependencyInjection\ContainerBuilder
    {
        \MonorepoBuilder20220204\Webmozart\Assert\Assert::allIsAOf($extensions, \MonorepoBuilder20220204\Symfony\Component\DependencyInjection\Extension\ExtensionInterface::class);
        \MonorepoBuilder20220204\Webmozart\Assert\Assert::allIsAOf($compilerPasses, \MonorepoBuilder20220204\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface::class);
        \MonorepoBuilder20220204\Webmozart\Assert\Assert::allString($configFiles);
        \MonorepoBuilder20220204\Webmozart\Assert\Assert::allFile($configFiles);
        $containerBuilder = new \MonorepoBuilder20220204\Symfony\Component\DependencyInjection\ContainerBuilder();
        $this->registerExtensions($containerBuilder, $extensions);
        $this->registerConfigFiles($containerBuilder, $configFiles);
        $this->registerCompilerPasses($containerBuilder, $compilerPasses);
        // this calls load() method in every extensions
        // ensure these extensions are implicitly loaded
        $compilerPassConfig = $containerBuilder->getCompilerPassConfig();
        $compilerPassConfig->setMergePass(new \MonorepoBuilder20220204\Symplify\SymplifyKernel\DependencyInjection\LoadExtensionConfigsCompilerPass());
        return $containerBuilder;
    }
    /**
     * @param ExtensionInterface[] $extensions
     */
    private function registerExtensions(\MonorepoBuilder20220204\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder, array $extensions) : void
    {
        foreach ($extensions as $extension) {
            $containerBuilder->registerExtension($extension);
        }
    }
    /**
     * @param CompilerPassInterface[] $compilerPasses
     */
    private function registerCompilerPasses(\MonorepoBuilder20220204\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder, array $compilerPasses) : void
    {
        foreach ($compilerPasses as $compilerPass) {
            $containerBuilder->addCompilerPass($compilerPass);
        }
    }
    /**
     * @param string[] $configFiles
     */
    private function registerConfigFiles(\MonorepoBuilder20220204\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder, array $configFiles) : void
    {
        $delegatingLoader = $this->loaderFactory->create($containerBuilder, \getcwd());
        foreach ($configFiles as $configFile) {
            $delegatingLoader->load($configFile);
        }
    }
}
